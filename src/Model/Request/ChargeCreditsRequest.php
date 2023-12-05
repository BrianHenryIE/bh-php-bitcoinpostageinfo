<?php

/**
 * To load credit to your BitcoinPostage.info account, you request a Bitcoin address to send the funds to.
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Request;

class ChargeCreditsRequest
{
    /** @var string API key */
    public readonly string $key;
    /** @var string API secret */
    public readonly string $secret;
    /** @var string Amount in USD you wish to charge */
    public readonly string $amount;

    public function __construct(
        Credentials $credentials,
        string $amount,
    ) {
        $this->key = $credentials->key;
        $this->secret = $credentials->secret;

        $this->amount = $amount;

        $this->validate();
    }

    protected function validate(): void
    {
        if (1 !== preg_match('/^\d+(\.\d{1,2})?$/', $this->amount)) {
            throw new \Exception('Amount must be a number with up to 2 decimal places.');
        }
    }
}
