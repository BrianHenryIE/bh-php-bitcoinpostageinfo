<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Address;
use BrianHenryIE\BitcoinPostageInfo\Model\Dimensions;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\GetRatesRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\Rate;
use BrianHenryIE\BitcoinPostageInfo\Model\Service;
use BrianHenryIE\BitcoinPostageInfo\Model\UspsPackage;

class GetRatesAPITest extends ContractTestCase
{
    public function testGetRates(): void
    {
        $api = self::getApi();

        $ratesRequest = new GetRatesRequest(
            credentials: new Credentials(
                key: $_ENV[ 'API_KEY' ],
                secret: $_ENV[ 'API_SECRET' ],
            ),
            fromAddress: new Address(
                name: 'Brian Henry',
                street: '800 N St.',
                street2: null,
                city: 'Sacramento',
                state: 'CA',
                zip: '95814',
                country: 'US',
                phone: '',
            ),
            toAddress: new Address(
                name: 'Brian Henry',
                street: '5000 Folsom Blvd',
                street2: null,
                city: 'Sacramento',
                state: 'CA',
                zip: '95819',
                country: 'US',
                phone: '',
            ),
            service: new Service(
                packageType: UspsPackage::PARCEL,
            ),
            dimensions: new Dimensions(
                weightLbs: 0,
                weightOz: 1.5,
                heightInches: 1.0,
                widthInches: 2.0,
                depthInches: 3.0,
            ),
        );

        /** @var Rate[] $result */
        $result = $api->getRates($ratesRequest);

        self::assertEquals('Priority Mail Express', $result[0]->serviceDisplay);
    }

    public function testGetRatesInternational(): void
    {
        $api = self::getApi();

        $ratesRequest = new GetRatesRequest(
            credentials: new Credentials(
                key: $_ENV[ 'API_KEY' ],
                secret: $_ENV[ 'API_SECRET' ],
            ),
            fromAddress: new Address(
                name: 'Brian Henry',
                street: '800 N St.',
                street2: null,
                city: 'Sacramento',
                state: 'CA',
                zip: '95814',
                country: 'US',
                phone: '',
            ),
            toAddress: new Address(
                name: 'Brian Henry',
                street: '1 Palace St',
                street2: 'Apartment 3',
                city: 'Dublin',
                state: 'Dublin',
                zip: 'D02 XR57',
                country: 'IE',
                phone: '',
            ),
            service: new Service(
                packageType: UspsPackage::PARCEL,
            ),
            dimensions: new Dimensions(
                weightLbs: 0,
                weightOz: 5,
                heightInches: 1.0,
                widthInches: 3.0,
                depthInches: 4.0
            ),
        );

        /** @var Rate[] $result */
        $result = $api->getRates($ratesRequest);

        self::assertEquals('Express Mail International', $result[0]->serviceDisplay);
    }
}
