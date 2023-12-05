<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Request\ChargeCreditsRequest;
use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;

class ChargeCreditsAPITest extends ContractTestCase
{
    public function testChargeCredits(): void
    {
        $api = self::getApi();

        $chargeCreditsRequest = new ChargeCreditsRequest(
            credentials: new Credentials(
                key: $_ENV[ 'API_KEY' ],
                secret: $_ENV[ 'API_SECRET' ],
            ),
            amount: '10.00'
        );

        $result = $api->chargeCredits($chargeCreditsRequest);

        self::assertEquals('10', $result->credits);
    }
}
