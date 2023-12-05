<?php

/**
 * @see https://bitcoinpostage.info/myaccount/api-access
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Request;

use BrianHenryIE\BitcoinPostageInfo\Model\Exception\BitcoinPostageInfoException;

class Credentials
{
    /**
     * @throws BitcoinPostageInfoException
     */
    public function __construct(
        public readonly string $key,
        public readonly string $secret,
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        if (empty($this->key)) {
            throw new BitcoinPostageInfoException('API key is required');
        }
        if (empty($this->secret)) {
            throw new BitcoinPostageInfoException('API secret is required');
        }
    }
}
