<?php

/**
 * Contains information relating to each product within the package. There can be several in one request.
 * Fields separated by :: Quantity, Description, Total Value($), Total Weight(oz), Country code of origin, Tariff Number
 * Example - 2::Books::100::20::US::49019900
 *
 * @see https://hts.usitc.gov/
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model;

class CustomsItem
{
    public function __construct(
        public readonly int $quantity,
        public readonly string $description,
        public readonly string $totalValue,
        public readonly float $totalWeightOz,
        public readonly string $countryCodeOfOrigin,
        public readonly string $hsTariffNumber,
    ) {
    }

    public function __toString(): string
    {
        return implode('::', [
            $this->quantity,
            $this->description,
            $this->totalValue,
            $this->totalWeightOz,
            $this->countryCodeOfOrigin,
            $this->hsTariffNumber,
        ]);
    }
}
