<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;
use BrianHenryIE\BitcoinPostageInfo\Model\Response\OrdersOrder;

class OrdersAPITest extends ContractTestCase
{
    public function testGetRates(): void
    {
        $api = self::getApi();

        $credentials = new Credentials(
            key: $_ENV[ 'API_KEY' ],
            secret: $_ENV[ 'API_SECRET' ],
        );

        $result = $api->orders($credentials);

        self::assertInstanceOf(OrdersOrder::class, $result[0]);
    }
}
