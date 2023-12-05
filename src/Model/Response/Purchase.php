<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model\Response;

class Purchase
{
    public function __construct(
        /** @var string $orderTimestamp The unix timestamp of the order. */
        public readonly string $orderTimestamp,
        /** @var PurchaseItem[] */
        public readonly array $items,
        /** @var string $orderId The order id generated on bitcoinpostage.info. */
        public readonly string $orderId,
    ) {
    }
}
