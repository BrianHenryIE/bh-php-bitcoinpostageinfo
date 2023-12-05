<?php

/**
 * https://bitcoinpostage.info/api/get_rates
 * https://btcpostage.com/api-documentation#get-rates
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Request;

use BrianHenryIE\BitcoinPostageInfo\Model\Address;
use BrianHenryIE\BitcoinPostageInfo\Model\Dimensions;
use BrianHenryIE\BitcoinPostageInfo\Model\Exception\BitcoinPostageInfoException;
use BrianHenryIE\BitcoinPostageInfo\Model\FedExPackage;
use BrianHenryIE\BitcoinPostageInfo\Model\Service;
use BrianHenryIE\BitcoinPostageInfo\Model\UpsPackage;
use BrianHenryIE\BitcoinPostageInfo\Model\UspsPackage;
use Exception;

class GetRatesRequest
{
    /** @var string $key  API key */
    public readonly string $key;

    /** @var string $secret  API secret */
    public readonly string $secret;
    /** @var string $to_name  Name of recipient */
    public readonly string $to_name;
    /** @var string $to_street  Street address of recipient */
    public readonly string $to_street;
    /** @var string $to_street2  Address Line 2 of recipient */
    public readonly ?string $to_street2;
    /** @var string $to_city  City of recipient */
    public readonly string $to_city;
    /** @var string $to_state  State of recipient */
    public readonly string $to_state;
    /** @var string $to_zip  ZIP code of recipient */
    public readonly string $to_zip;
    /** @var string $to_country  Country of recipient */
    public readonly string $to_country;
    /** @var string $to_phone  Phone number of recipient */
    public readonly string $to_phone;
    /** @var string $from_name  Name of sender */
    public readonly string $from_name;
    /** @var string $from_street  Street address of sender */
    public readonly string $from_street;
    /** @var string $from_street2  Address Line 2 of sender */
    public readonly ?string $from_street2;
    /** @var string $from_city  City of sender */
    public readonly string $from_city;
    /** @var string $from_state  State of sender */
    public readonly string $from_state;
    /** @var string $from_zip  ZIP code of sender */
    public readonly string $from_zip;
    /** @var string $from_country  Country of sender */
    public readonly string $from_country;
    /** @var string $from_phone  Phone number of sender */
    public readonly string $from_phone;
    /** @var string $package_type */
    public readonly string $package_type;
    /** @var string $carrier */
    public readonly string $carrier;
    /** @var string $service  Service chosen from the Get Rates endpoint output */
    public readonly ?string $service;
    /** @var ?string $serviceoption  Only if using FedEx or UPS */
    public readonly ?string $serviceoption;
    /** @var int $weight_lbs  Pounds of package weight (integer - submit remainder as ounces in `weight_oz` parameter) */
    public readonly int $weight_lbs;
    /** @var float $weight_oz  Ounces remainder of package weight (not full weight in ounces) */
    public readonly float $weight_oz;
    /** @var float $height  Inches of package height */
    public readonly float $height;
    /** @var float $width  Inches of package width */
    public readonly float $width;
    /** @var float $depth  Inches of package depth/length */
    public readonly float $depth;

    public function __construct(
        Credentials $credentials,
        Address $fromAddress,
        Address $toAddress,
        Service $service,
        Dimensions $dimensions,
    ) {
        $this->key    = $credentials->key;
        $this->secret = $credentials->secret;

        $this->to_name       = $toAddress->name;
        $this->to_street     = $toAddress->street;
        $this->to_street2    = $toAddress->street2;
        $this->to_city       = $toAddress->city;
        $this->to_state      = $toAddress->state;
        $this->to_zip        = $toAddress->zip;
        $this->to_country    = $toAddress->country;
        $this->to_phone      = $toAddress->phone;

        $this->from_name     = $fromAddress->name;
        $this->from_street   = $fromAddress->street;
        $this->from_street2  = $fromAddress->street2;
        $this->from_city     = $fromAddress->city;
        $this->from_state    = $fromAddress->state;
        $this->from_zip      = $fromAddress->zip;
        $this->from_country  = $fromAddress->country;
        $this->from_phone    = $fromAddress->phone;

        $this->package_type  = $service->packageType->value;
        $this->carrier       = $this->getCarrierFromPackage($service->packageType);
        $this->service       = $service->service;
        $this->serviceoption = $service->serviceOption;

        $this->depth      = $dimensions->depthInches;
        $this->width      = $dimensions->widthInches;
        $this->height     = $dimensions->heightInches;
        $this->weight_lbs = $dimensions->weightLbs;
        $this->weight_oz  = $dimensions->weightOz;

        $this->validate();
    }

    /**
     * @throws Exception
     */
    protected function validate(): void
    {
        if (
            in_array($this->carrier, [ 'fedex', 'ups' ])
            && (empty($this->from_phone) || empty($this->to_phone))
        ) {
            throw new BitcoinPostageInfoException('Phone number required for FedEx and UPS.');
        }
    }

    protected function getCarrierFromPackage(UspsPackage|FedExPackage|UpsPackage $packageType): string
    {
        $carriers = [
            UspsPackage::class => 'usps',
            FedExPackage::class => 'fedex',
            UpsPackage::class => 'ups',
        ];
        return $carriers[ $packageType::class ];
    }
}
