<?php

/**
 * Loads `.env.secret` into `$_ENV` and provides a method to get the API object.
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

class ContractTestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = parse_ini_file(dirname(__DIR__, 1) . '/.env.secret') ?: [];
        foreach ($env as $item => $value) {
            if (str_starts_with($value, '#')) {
                continue;
            }
            $_ENV[ $item ] = $value;
        }

        if (! isset($_ENV[ 'API_KEY' ]) || ! isset($_ENV[ 'API_SECRET' ])) {
            self::markTestSkipped('API_KEY and API_SECRET must be set in .env.secret');
        }
    }

    protected static function getApi(): BitcoinPostageInfoAPI
    {

        $requestFactory = new HttpFactory();
        $client         = new Client();

        return new BitcoinPostageInfoAPI($requestFactory, $requestFactory, $client);
    }
}
