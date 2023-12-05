<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;

class OrdersTest extends MockHttpTestCase
{
    protected const ENDPOINT = 'https://bitcoinpostage.info/api/orders';

    public function testOrders(): void
    {
        $response = file_get_contents(dirname(__DIR__) . '/_data/orders.json');
        $sut = self::getMockClient(self::ENDPOINT, $response);

        $ordersRequest = new Credentials(
            key: 'key',
            secret: 'secret'
        );

        $result = $sut->orders(credentials: $ordersRequest);

        self::assertCount(2, $result);
        self::assertEquals("22.36", $result[1]->price);
    }
}
