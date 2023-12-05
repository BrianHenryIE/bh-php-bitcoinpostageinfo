<?php

/**
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Exception;

use Throwable;

class InvalidCredentialsException extends BitcoinPostageInfoException
{
    public const RESPONSE_BODY = 'Could not verify API key. Please check key and secret.';
    public const CODE = 100;
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(self::RESPONSE_BODY, self::CODE, $previous);
    }
}
