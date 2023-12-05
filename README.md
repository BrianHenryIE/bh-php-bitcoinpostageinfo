[![PHP 8.1](https://img.shields.io/badge/PHP-8.1-8892BF.svg)]() [![PHPCS PSR-12](https://img.shields.io/badge/PHPCS-PSR–12-226146.svg)](https://www.php-fig.org/psr/psr-12/) [![PHPUnit ](.github/coverage.svg)](https://brianhenryie.github.io/bh-php-bitcoinpostageinfo/) [![PHPStan ](.github/phpstan.svg)](https://phpstan.org/)

# BitcoinPostage.info PHP API

A thin PHP wrapper for [BitcoinPostage.info](http://bitcoinpostage.info/) – a website/API which sells USPS, UPS and FedEx postage and accepts Bitcoin and Monero for payment.

```bash
composer require brianhenryie/bh-php-bitcoinpostageinfo
```
See:

* [API Documentation](https://btcpostage.com/api-documentation) (shows example cURL requests for each endpoint)
* [Register](https://bitcoinpostage.info/register)
* [Create an API key](https://bitcoinpostage.info/myaccount/api-access)


## Use

Create an instance of the API class with a PSR HTTP implementation. [Guzzle](https://github.com/guzzle/guzzle) is the most popular.
```php
$httpFactory = new \GuzzleHttp\Psr7\HttpFactory();
$client      = new \GuzzleHttp\Client();

$bitcoinPostageInfo = new \BrianHenryIE\BitcoinPostageInfo\BitcoinPostageInfoAPI(
    requestFactory: $httpFactory,
    streamFactory: $httpFactory,
    client: $client,
);
```

All API calls require authentication:
```php
$credentials = new \BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials(
    key: $_ENV[ 'BITCOINPOSTAGEINFO_API_KEY' ],
    secret: $_ENV[ 'BITCOINPOSTAGEINFO_API_SECRET' ],
);
```

Add credit to your account:
```php
$chargeCreditsRequest = new \BrianHenryIE\BitcoinPostageInfo\Model\Request\ChargeCreditsRequest(
    credentials: $credentials,
    amount: '10.00'
);

/** @var \BrianHenryIE\BitcoinPostageInfo\Model\Response\ChargeCredits $chargeCreditsResponse */
$chargeCreditsResponse = $api->chargeCredits($chargeCreditsRequest);

$bitcoinAddressHref = 'bitcoin:' . $chargeCreditsResponse->address . '?amount=' . $chargeCreditsResponse->amount;
$bitcoinQrCode = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $bitcoinAddressHref . '&choe=UTF-8';

printf(
    '<a href="%s" target="_blank"><img src="%s"/><br/>Pay BitcoinPostage.info for $%s credits.</a>',
    $bitcoinAddressHref,
    $bitcoinQrCode,
    $chargeCreditsResponse->credits
);
```

Check your account balance:
```php
$credits = $api->getCredits($credentials);

echo $credits->credits;
```

You'll need your from and to addresses and the package dimensions to get rates and to purchase labels:

```php
$fromAddress = new \BrianHenryIE\BitcoinPostageInfo\Model\Address(
    name: 'Brian Henry',
    street: '800 N St.',
    street2: null,
    city: 'Sacramento',
    state: 'CA',
    zip: '95814',
    country: 'US',
    phone: null,
);
$toAddress = new \BrianHenryIE\BitcoinPostageInfo\Model\Address(
    name: 'Brian Henry',
    street: '1 Palace St',
    street2: 'Apartment 3',
    city: 'Dublin',
    state: 'Dublin',
    zip: 'D02 XR57',
    country: 'IE',
    phone: null,
);
$dimensions = new \BrianHenryIE\BitcoinPostageInfo\Model\Dimensions(
    weightLbs: 0,
    weightOz: 1.5,
    heightInches: 1.0,
    widthInches: 2.0,
    depthInches: 3.0,
);
```

Query for rates:
```php
$ratesRequest = new \BrianHenryIE\BitcoinPostageInfo\Model\Request\GetRatesRequest(
    credentials: $credentials,
    fromAddress: $fromAddress,
    toAddress: $toAddress,
    service: new \BrianHenryIE\BitcoinPostageInfo\Model\Service(
        packageType: UspsPackage::FLAT_RATE_ENVELOPE,
    ),
    dimensions: $dimensions,
);

/** @var \BrianHenryIE\BitcoinPostageInfo\Model\Response\Rate[] $ratesResponse */
$ratesResponse = $api->getRates($ratesRequest);

$cheapest_rate = array_reduce(
    $ratesResponse,
    function (\BrianHenryIE\BitcoinPostageInfo\Model\Response\Rate $carry, \BrianHenryIE\BitcoinPostageInfo\Model\Response\Rate $rate) {
        return is_null($carry) || $rate->rate < $carry->rate ? $rate : $carry;
    },
    null
);
echo "{$cheapest_rate->currency} {$cheapest_rate->rate}";
echo $cheapest_rate->service;
```

International shipments require customs information:
```php
$customs = new Customs(
    type: CustomsContentsType::GIFT,
    signer: 'Brian Henry',
    customs: [
         new \BrianHenryIE\BitcoinPostageInfo\Model\CustomsItem(
             quantity: 1,
             description: 'T-shirt',
             totalValue: '10.00',
             totalWeightOz: 20.5,
             countryCodeOfOrigin: 'US',
             hsTariffNumber: '610910',
         )
    ],
);
```

Purchase a label:
```php
$createPurchaseRequest = new \BrianHenryIE\BitcoinPostageInfo\Model\Request\CreatePurchaseRequest(
    credentials: $credentials,
    fromAddress: $fromAddress,
    toAddress: $toAddress,
    service: new \BrianHenryIE\BitcoinPostageInfo\Model\Service(
        packageType: UspsPackage::FLAT_RATE_ENVELOPE,
        service: 'usps_intl_first_class_package',
    ),
    dimensions: $dimensions,
    customs: $customs,
    testMode: true,
);

/** @var \BrianHenryIE\BitcoinPostageInfo\Model\Response\Purchase $purchaseResponse */
$purchaseResponse = $api->createPurchase($createPurchaseRequest);

/** @var \BrianHenryIE\BitcoinPostageInfo\Model\Response\PurchaseItem $purchasedLabel */
$purchasedLabel = $purchaseResponse->items[0];

echo $purchasedLabel->price;
echo $purchasedLabel->filename;
echo $purchasedLabel->trackingNo;
```

## Notes

* Currency amounts are strings and other numbers are floats, except for `weightLbs` which is an int
* A good PHP library for generating QR codes is [chillerlan/php-qrcode](https://github.com/chillerlan/php-qrcode) (for the `::chargeCredits()` payment address) 
* [Guzzle](https://github.com/guzzle/guzzle) is a great PSR HTTP library but consider [Art4/WP-Requests-PSR18-Adapter](https://github.com/Art4/WP-Requests-PSR18-Adapter) if you are developing for WordPress
* USPS will send you free flat rate envelopes and boxes. [Order them online](https://store.usps.com/store/results/shipping-supplies/_/N-7d0v8v) or pick them up at your local post office.

## Contribute

PhpDoc is incomplete. I'm not sure the correct way to document PHP 8.0 constructor property promotion properties.

What's a better coding standard to use?

No testing has been done for UPS / FedEx.

## Acknowledgements

The [BitcoinPostage.info support](https://btcpostage.com/faq#contact) was very quick to reply to emails and update documentation as requested.