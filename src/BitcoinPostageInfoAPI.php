<?php

/**
 * PHP wrapper for the BitcoinPostage.info API.
 *
 * BitcoinPostage.info is a service that allows you to purchase USPS, UPS and FedEx postage with Bitcoin and Monero.
 *
 * @see https://btcpostage.com/api-documentation
 * @see https://bitcoinpostage.info/myaccount/api-access
 *
 * @package brianhenryie/bh-wp-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Exception\BitcoinPostageInfoException;
use BrianHenryIE\BitcoinPostageInfo\Model\Exception\InsufficientCreditsException;
use BrianHenryIE\BitcoinPostageInfo\Model\Exception\InvalidCredentialsException;
use BrianHenryIE\BitcoinPostageInfo\Model\Exception\NoRatesException;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\ChargeCreditsRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\CreatePurchaseRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\GetRatesRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\RetrieveOrderRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\RetrievePurchaseRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\ChargeCredits;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\Credits;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\OrdersOrder;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\Purchase;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\Rate;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\RetrieveOrderOrder;
use JsonMapper\Enums\TextNotation;
use JsonMapper\Exception\BuilderException;
use JsonMapper\Handler\FactoryRegistry;
use JsonMapper\Handler\PropertyMapper;
use JsonMapper\JsonMapperBuilder;
use JsonMapper\Middleware\CaseConversion;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use UnexpectedValueException;

/**
 * All API calls require authentication.
 *
 * @throws InvalidCredentialsException
 */
class BitcoinPostageInfoAPI
{
    protected const API_ROOT = 'https://bitcoinpostage.info/api/';

    /**
     * Constructor
     *
     * @param RequestFactoryInterface $requestFactory PSR HTTP implementation.
     * @param ClientInterface $client PSR HTTP client for making requests.
     * @param string            $url The server to query.
     */
    public function __construct(
        protected RequestFactoryInterface $requestFactory,
        protected StreamFactoryInterface $streamFactory,
        protected ClientInterface $client,
        protected string $url = self::API_ROOT
    ) {
    }

    /**
     * Retrieves your current credit balance.
     *
     * Returns an object with a single property, "credits", which is the account balance in USD.
     *
     * @see https://btcpostage.com/api-documentation/#get-credits
     */
    public function getCredits(Credentials $request): Credits
    {
        return $this->callApi(
            'get-credits',
            $request,
            Credits::class
        );
    }

    /**
     * Creates a pending transaction to charge your account. Once you complete the bitcoin transaction to the address
     * specified, your account will automatically be charged. Please note that sending the wrong amount will result in
     * failing to charge your account, and any given address is only usable for a single transaction.
     *
     * Send the amount of credit you wish to add to your account as USD in the amount parameter.
     * Returns on object including the BTC amount and Bitcoin address to send payment to.
     *
     * @see https://btcpostage.com/api-documentation/#charge-credits
     */
    public function chargeCredits(ChargeCreditsRequest $request): ChargeCredits
    {
        return $this->callApi(
            'charge-credits',
            $request,
            ChargeCredits::class
        );
    }

    /**
     * Fetch the available postage services and rates for a given package.
     *
     * @see https://btcpostage.com/api-documentation/#get-rates
     * @return Rate[]
     */
    public function getRates(GetRatesRequest $request): array
    {
        return $this->callApi(
            'get-rates',
            $request,
            Rate::class
        );
    }

    /**
     * Purchases a shipping label.
     *
     * NB: this function uses your account's credit balance.
     * Set `testMode` to true to test purchasing USPS labels without using credits.
     *
     * @see https://btcpostage.com/api-documentation/#create-purchase
     *
     * @throws InsufficientCreditsException
     */
    public function createPurchase(CreatePurchaseRequest $request): Purchase
    {
        return $this->callApi(
            'create-purchase',
            $request,
            Purchase::class
        );
    }

    /**
     * Retrieves your orders list.
     *
     * The orders list contains the order id, time and cost. Details of each order can be retrieved using the
     * {@see self::retrieveOrder()} API call.
     *
     * @see https://btcpostage.com/api-documentation/#orders
     * @return OrdersOrder[]
     */
    public function orders(Credentials $credentials): array
    {
        return $this->callApi(
            'orders',
            $credentials,
            OrdersOrder::class
        );
    }

    /**
     * @see https://btcpostage.com/api-documentation/#retrieve-order
     *
     * @return RetrieveOrderOrder[]
     */
    public function retrieveOrder(RetrieveOrderRequest $request): array
    {
        return $this->callApi(
            'retrieve-order',
            $request,
            RetrieveOrderOrder::class
        );
    }

    /**
     * @see https://btcpostage.com/api-documentation/#retrieve-purchase
     */
    public function retrievePurchase(RetrievePurchaseRequest $request): Purchase
    {
        return $this->callApi(
            'retrieve-purchase',
            $request,
            Purchase::class
        );
    }

    /**
     * Queries the API via PSR client and casts the value to an object.
     *
     * @template T of object
     * @param string $endpoint The REST route, excluding the domain.
     * @param object $requestBody
     * @param class-string<T> $type The object type to cast/deserialize the response to.
     *
     * @return T|array<T>
     *
     * @throws BitcoinPostageInfoException
     * @throws ClientExceptionInterface PSR HTTP client exception.
     * @throws InsufficientCreditsException
     * @throws InvalidCredentialsException
     * @throws BuilderException
     */
    protected function callApi(string $endpoint, object $requestBody, string $type)
    {
        if (! class_exists($type)) {
            throw new UnexpectedValueException("{$type} class does not exist");
        }

        $request = $this->requestFactory->createRequest(
            'POST',
            "{$this->url}{$endpoint}"
        )->withBody(
            $this->streamFactory->createStream(
                http_build_query($requestBody)
            )
        )->withHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );

        $response = $this->client->sendRequest($request);

        if (2 !== (int) ($response->getStatusCode() / 100)) {
            throw new BitcoinPostageInfoException('error.');
        }

        $responseBody = (string) $response->getBody();

        // Could not verify API key. Please check key and secret.
        if ($responseBody === InvalidCredentialsException::RESPONSE_BODY) {
            throw new InvalidCredentialsException();
        }

        // No valid price quotes found. Please check your request and try again, or please contact support.
        // Common issues: incorrect carrier/package/service type.
        if ($responseBody === NoRatesException::RESPONSE_BODY) {
            throw new NoRatesException();
        }

        // Error: insufficient credits. Label would cost 9.35, but only 0 available.
        if (1 === preg_match(InsufficientCreditsException::REGEX, $responseBody, $output_array)) {
            throw new InsufficientCreditsException($output_array);
        }

        $factoryRegistry = new FactoryRegistry();
        $mapper = JsonMapperBuilder::new()
                                   ->withDocBlockAnnotationsMiddleware()
                                   ->withObjectConstructorMiddleware($factoryRegistry)
                                   ->withPropertyMapper(new PropertyMapper($factoryRegistry))
                                   ->withTypedPropertiesMiddleware()
                                   ->withNamespaceResolverMiddleware()
                                   ->build();

        $mapper->push(new CaseConversion(TextNotation::UNDERSCORE(), TextNotation::CAMEL_CASE()));

        switch ($type) {
            case Rate::class:
            case OrdersOrder::class:
            case RetrieveOrderOrder::class:
                return $mapper->mapToClassArrayFromString($responseBody, $type);
            default:
                return $mapper->mapToClassFromString($responseBody, $type);
        }
    }
}
