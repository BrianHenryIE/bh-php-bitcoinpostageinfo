<?php

/**
 * Error: insufficient credits. Label would cost 9.35, but only 0 available.
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Exception;

use Throwable;

class InsufficientCreditsException extends BitcoinPostageInfoException
{
    protected string $cost;
    protected string $available;
    public const CODE = 102;

    public const REGEX = '/^Error: insufficient credits. Label would cost (?<cost>\d+\.?\d{0,2}), but only '
                         . '(?<available>\d+\.?\d{0,2}) available.$/';

    /**
     * @param array{0:string,cost:string,available:string} $caputured Regex output array.
     * @param ?Throwable $previous
     */
    public function __construct(array $caputured, ?Throwable $previous = null)
    {
        $this->cost = $caputured['cost'];
        $this->available = $caputured['available'];
        parent::__construct($caputured[0], self::CODE, $previous);
    }

    public function getCost(): string
    {
        return $this->cost;
    }
    public function getAvailable(): string
    {
        return $this->available;
    }
}
