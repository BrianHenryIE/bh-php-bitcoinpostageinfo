<?php

namespace BrianHenryIE\BitcoinPostageInfo;

use PsrMock\Psr17\RequestFactory;
use PsrMock\Psr17\StreamFactory;
use PsrMock\Psr18\Client;
use PsrMock\Psr7\Response;

class MockHttpTestCase extends \PHPUnit\Framework\TestCase
{
    protected static function getMockClient(string $path, string|bool $responseBody): BitcoinPostageInfoAPI
    {
        $httpFactory   = new RequestFactory();
        $streamFactory = new StreamFactory();
        $client        = new Client();

        $sut = new BitcoinPostageInfoAPI(
            $httpFactory,
            $streamFactory,
            $client,
        );

        $responseStream = $streamFactory->createStream("$responseBody");

        $response = ( new Response() )->withBody($responseStream);

        $client->addResponse(
            'POST',
            $path,
            $response
        );

        return $sut;
    }
}
