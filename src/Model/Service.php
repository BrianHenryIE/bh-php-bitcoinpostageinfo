<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model;

class Service
{
    public function __construct(
        /**
         * @see https://bitcoinpostage.info/link-builder-documentation#package_type
         */
        public readonly UspsPackage|FedExPackage|UpsPackage $packageType,
        public readonly ?string $serviceOption = null, // Only if using FedEx or UPS
        public readonly ?string $service = null, // Service chosen from the Get Rates endpoint output
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        if (!is_null($this->serviceOption) && ($this->packageType instanceof UspsPackage)) {
            throw new \InvalidArgumentException('Service option only valid for FedEx or UPS.');
        }
    }
}
