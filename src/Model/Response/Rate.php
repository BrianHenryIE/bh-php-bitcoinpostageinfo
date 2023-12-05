<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model\Response;

class Rate
{
    public function __construct(
        public readonly string $service,
        public readonly string $serviceDisplay,
        public readonly string $rate,
        public readonly string $carrier,
        public readonly string $estDeliveryDays,
        public readonly string $currency,
    ) {
    }
}
