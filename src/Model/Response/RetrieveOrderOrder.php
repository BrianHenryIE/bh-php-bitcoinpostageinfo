<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model\Response;

class RetrieveOrderOrder
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly string $trackingNo,
        public readonly string $shipmentId,
        public readonly string $carrier
    ) {
    }
}
