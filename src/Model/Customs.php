<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model;

use BrianHenryIE\BitcoinPostageInfo\Model\Exception\BitcoinPostageInfoException;
use Exception;

class Customs
{
    /**
     * @param CustomsContentsType $type Type of Contents: Documents, Gift, Merchandise, Returned Goods, Sample
     * @param string $signer Signer / Name
     * @param non-empty-array<CustomsItem> $items
     *
     * @throws BitcoinPostageInfoException
     */
    public function __construct(
        public readonly CustomsContentsType $type,
        public readonly string $signer,
        public readonly array $items,
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        if (empty($this->signer)) {
            throw new BitcoinPostageInfoException('Customs signer is required');
        }
        if (empty($this->items)) {
            throw new BitcoinPostageInfoException('Customs items are required');
        }
    }
}
