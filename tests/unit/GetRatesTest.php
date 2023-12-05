<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Address;
use BrianHenryIE\BitcoinPostageInfo\Model\Dimensions;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\GetRatesRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Service;
use BrianHenryIE\BitcoinPostageInfo\Model\UspsPackage;

class GetRatesTest extends MockHttpTestCase
{
    protected const ENDPOINT = 'https://bitcoinpostage.info/api/get-rates';

    public function testGetRates(): void
    {
        $response = file_get_contents(dirname(__DIR__) . '/_data/get-rates.json');
        $sut = self::getMockClient(self::ENDPOINT, $response);

        $ratesRequest = new GetRatesRequest(
            credentials: new Credentials(
                key: 'key',
                secret: 'secret',
            ),
            fromAddress: new Address(
                name: 'Brian Henry',
                street: '123 Fake St',
                street2: null,
                city: 'Sacramento',
                state: 'CA',
                zip: '95816',
                country: 'US',
                phone: '',
            ),
            toAddress: new Address(
                name: 'Brian Henry',
                street: '123 Fake St',
                street2: null,
                city: 'Sacramento',
                state: 'CA',
                zip: '95819',
                country: 'US',
                phone: '',
            ),
            service: new Service(
                packageType: UspsPackage::PARCEL,
                serviceOption: null,
            ),
            dimensions: new Dimensions(
                weightLbs: 0,
                weightOz: 5,
                heightInches: 3.0,
                widthInches: 3.0,
                depthInches: 3.0,
            ),
        );

        $result = $sut->getRates($ratesRequest);

        self::assertCount(5, $result);
    }
}
