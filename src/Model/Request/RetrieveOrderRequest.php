<?php

/**
 * To load credit to your BitcoinPostage.info account, you request a Bitcoin address to send the funds to.
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Request;

class RetrieveOrderRequest
{
    /** @var string API key */
    public readonly string $key;
    /** @var string API secret */
    public readonly string $secret;

    public readonly string $order_id;

    public function __construct(
        Credentials $credentials,
        string $orderId,
    ) {
        $this->key    = $credentials->key;
        $this->secret = $credentials->secret;

        $this->order_id = $orderId;
    }
}
