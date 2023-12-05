<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model\Response;

class OrdersOrder
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $orderTimestamp,
        public readonly string $price
    ) {
    }
}
