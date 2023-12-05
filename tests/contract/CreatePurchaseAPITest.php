<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Address;
use BrianHenryIE\BitcoinPostageInfo\Model\Customs;
use BrianHenryIE\BitcoinPostageInfo\Model\CustomsContentsType;
use BrianHenryIE\BitcoinPostageInfo\Model\CustomsItem;
use BrianHenryIE\BitcoinPostageInfo\Model\Dimensions;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\CreatePurchaseRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;
use BrianHenryIE\BitcoinPostageInfo\Model\Service;
use BrianHenryIE\BitcoinPostageInfo\Model\UspsPackage;

class CreatePurchaseAPITest extends ContractTestCase
{
    public function testCreatePurchase(): void
    {
        $api = self::getApi();

        $createPurchaseRequest = new CreatePurchaseRequest(
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
                service: 'usps_first_class',
            ),
            dimensions: new Dimensions(
                weightLbs: 0,
                weightOz: 2,
                heightInches: 1.0,
                widthInches: 3.0,
                depthInches: 3.0,
            ),
            testMode: true,
        );

        $result = $api->createPurchase($createPurchaseRequest);

        self::expectNotToPerformAssertions();
    }


    public function testCreatePurchaseInternational(): void
    {
        $api = self::getApi();

        $createPurchaseRequest = new CreatePurchaseRequest(
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
                packageType: UspsPackage::FLAT_RATE_ENVELOPE,
                service: 'usps_intl_first_class_package',
            ),
            dimensions: new Dimensions(
                weightLbs: 0,
                weightOz: 2,
                heightInches: 2.0,
                widthInches: 3.0,
                depthInches: 3.0,
            ),
            customs: new Customs(
                type: CustomsContentsType::GIFT,
                signer: 'Brian Henry',
                items: [
                     new CustomsItem(
                         quantity: 1,
                         description: 'T-shirt',
                         totalValue: "10",
                         totalWeightOz: 20,
                         countryCodeOfOrigin: 'US',
                         hsTariffNumber: '610910',
                     )
                ],
            ),
            testMode: true,
        );

        $result = $api->createPurchase($createPurchaseRequest);

        self::expectNotToPerformAssertions();
    }
}
