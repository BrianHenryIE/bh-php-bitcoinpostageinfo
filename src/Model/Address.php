<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model;

use BrianHenryIE\BitcoinPostageInfo\Model\Exception\BitcoinPostageInfoException;

class Address
{
    /**
     * @throws BitcoinPostageInfoException
     */
    public function __construct(
        public readonly string $name, // Name of recipient
        public readonly string $street, // Street address of recipient
        public readonly ?string $street2, // Address Line 2 of recipient
        public readonly string $city, // City of recipient
        public readonly string $state, // State of recipient
        public readonly string $zip, // ZIP code of recipient
        public readonly string $country, // Country of recipient
        public readonly string $phone, // Phone number of recipient
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        $address = (array) $this;
        // Phone is optional for USPS but not for UPS/FedEx.
        unset($address['street2'], $address['phone']);
        foreach ($address as $key => $value) {
            if (empty($value)) {
                throw new BitcoinPostageInfoException('Address ' . $key . ' is required');
            }
        }
    }
}
