<?php

/**
 * Response containing the amount in Bitcoin and the address to send funds to, to add credit.
 *
 * @see https://btcpostage.com/api-documentation/#charge-credits
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Response;

use DateTimeImmutable;
use DateTimeInterface;

class ChargeCredits
{
    public function __construct(
        /**
         * @var string $address The Bitcoin payment address to send funds to.
         */
        public readonly string $address,
        public readonly string $id,
        /**
         * @var string $amount BTC value of the credits being purchased.
         */
        public readonly string $amount,
        public readonly string $timestamp,
        /**
         * @var string $amount USD value of the credits being purchased.
         */
        public readonly string $credits,
        public readonly string $purchaseId
    ) {
    }

    public function getTime(): DateTimeInterface
    {
        return new DateTimeImmutable('@' . $this->timestamp);
    }
}
