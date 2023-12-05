<?php

/**
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Exception;

use Throwable;

class NoRatesException extends BitcoinPostageInfoException
{
    public const RESPONSE_BODY = '{"message":"No valid price quotes found. Please check your request and try again, or '
                                 . 'please contact support. Common issues: incorrect carrier/package/service type."}';
    public const CODE = 101;
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(self::RESPONSE_BODY, self::CODE, $previous);
    }
}
