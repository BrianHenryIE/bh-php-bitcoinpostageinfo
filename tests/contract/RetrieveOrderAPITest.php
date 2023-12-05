<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\RetrieveOrderRequest;

class RetrieveOrderAPITest extends ContractTestCase
{
    public function testGetRates(): void
    {
        if (! isset($_ENV[ 'ORDER_ID' ]) || ! isset($_ENV[ 'RETRIEVE_ORDER_SHIPMENT_ID' ])) {
            self::markTestSkipped('Sample ORDER_ID and RETRIEVE_ORDER_SHIPMENT_ID must be set in .env.secret');
        }

        $api = self::getApi();

        $request = new RetrieveOrderRequest(
            credentials: new Credentials(
                key: $_ENV[ 'API_KEY' ],
                secret: $_ENV[ 'API_SECRET' ],
            ),
            orderId: $_ENV[ 'ORDER_ID' ]
        );

        $result = $api->retrieveOrder($request);

        self::assertEquals($_ENV['RETRIEVE_ORDER_SHIPMENT_ID'], $result[0]->shipmentId);
    }
}
