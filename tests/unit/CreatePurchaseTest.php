<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Address;
use BrianHenryIE\BitcoinPostageInfo\Model\Dimensions;
use BrianHenryIE\BitcoinPostageInfo\Model\Exception\InsufficientCreditsException;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\CreatePurchaseRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\Purchase;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\PurchaseItem;
use BrianHenryIE\BitcoinPostageInfo\Model\Service;
use BrianHenryIE\BitcoinPostageInfo\Model\UspsPackage;

class CreatePurchaseTest extends MockHttpTestCase
{
    protected const ENDPOINT = 'https://bitcoinpostage.info/api/create-purchase';

    public function testInsufficentCredit(): void
    {
        $response = 'Error: insufficient credits. Label would cost 9.35, but only 0 available.';
        $sut = self::getMockClient(self::ENDPOINT, $response);

        $createPurchaseRequest = new CreatePurchaseRequest(
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
                service: 'usps_priority',
            ),
            dimensions: new Dimensions(
                weightLbs: 0,
                weightOz: 5,
                heightInches: 3.0,
                widthInches: 3.0,
                depthInches: 3.0,
            ),
            testMode: true,
        );

        self::expectException(InsufficientCreditsException::class);

        $sut->createPurchase($createPurchaseRequest);
    }

    /**
     * It turns out constructor property promotion + JsonMapper array typing from docblocks doesn't work together.
     */
    public function testSuccessfulPurchase(): void
    {
        $response = file_get_contents(dirname(__DIR__) . '/_data/create-purchase.json');
        $sut = self::getMockClient(self::ENDPOINT, $response);

        $createPurchaseRequest = new CreatePurchaseRequest(
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
                zip: '95819',
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
                service: 'usps_priority',
            ),
            dimensions: new Dimensions(
                weightLbs: 0,
                weightOz: 5,
                heightInches: 3.0,
                widthInches: 3.0,
                depthInches: 3.0,
            ),
            testMode: true,
        );

        /** @var Purchase $result */
        $result = $sut->createPurchase($createPurchaseRequest);

        self::assertInstanceOf(PurchaseItem::class, $result->items[0]);

        self::assertEquals("1563268442", $result->orderTimestamp);
    }
}
