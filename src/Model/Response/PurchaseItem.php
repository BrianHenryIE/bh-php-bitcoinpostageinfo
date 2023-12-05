<?php

/**
 * There's actually much more returned in the response, but this is all that is documented on the website.
 *
 * @see https://btcpostage.com/api-documentation/#create-purchase
 *
 * @package brianhenryie/bh-php-bitcoinpostageinfo
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model\Response;

class PurchaseItem
{
    public function __construct(
        public readonly string $shipmentId,
        public readonly string $carrier,
        public readonly string $service,
        public readonly string $fromName,
        public readonly string $toName,
        public readonly string $price,
        public readonly string $currency,
        public readonly string $filename,
        public readonly string $trackingNo
    ) {
    }
}
