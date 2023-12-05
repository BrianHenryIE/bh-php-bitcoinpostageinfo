<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use BrianHenryIE\BitcoinPostageInfo\Model\Request\Credentials;

class GetCreditsAPITest extends ContractTestCase
{
    public function testGetCredits(): void
    {
        $api = self::getApi();

        $getCreditsRequest = new Credentials(
            key: $_ENV[ 'API_KEY' ],
            secret: $_ENV[ 'API_SECRET' ],
        );

        $result = $api->getCredits($getCreditsRequest);

        self::assertIsNumeric($result->credits);
    }
}
