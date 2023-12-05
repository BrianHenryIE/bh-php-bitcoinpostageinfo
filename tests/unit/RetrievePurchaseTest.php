<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\RetrievePurchaseRequest;

class RetrievePurchaseTest extends MockHttpTestCase
{
    protected const ENDPOINT = 'https://bitcoinpostage.info/api/retrieve-purchase';

    public function testRetrievePurchase(): void
    {
        $response = file_get_contents(dirname(__DIR__) . '/_data/retrieve-purchase.json');
        $sut = self::getMockClient(self::ENDPOINT, $response);

        $retrievePurchaseRequest = new RetrievePurchaseRequest(
            credentials: new Credentials(
                key: 'key',
                secret: 'secret',
            ),
            orderId: 'a5e',
        );

        $result = $sut->retrievePurchase($retrievePurchaseRequest);

        self::assertCount(1, $result->items);
    }
}
