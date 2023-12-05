<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model\Request;

use BrianHenryIE\BitcoinPostageInfo\Model\Address;
use BrianHenryIE\BitcoinPostageInfo\Model\Customs;
use BrianHenryIE\BitcoinPostageInfo\Model\CustomsItem;
use BrianHenryIE\BitcoinPostageInfo\Model\Dimensions;
use BrianHenryIE\BitcoinPostageInfo\Model\Exception\BitcoinPostageInfoException;
use BrianHenryIE\BitcoinPostageInfo\Model\Service;
use Exception;

class CreatePurchaseRequest extends GetRatesRequest
{
    /** @var ?string $type_contents, Type of Contents: Documents, Gift, Merchandise, Returned Goods, Sample */
    public readonly ?string $type_contents;
    /** @var ?string $signer, Signer / Name */
    public readonly ?string $signer;
    /** @var ?string[] $customs, */
    public readonly ?array $customs;
    /** @var ?string $test_mode Allows you to test the work of the api, without withdrawing credits. Works only for USPS. */
    public readonly ?string $test_mode;

    public function __construct(
        Credentials $credentials,
        Address $fromAddress,
        Address $toAddress,
        Service $service,
        Dimensions $dimensions,
        ?Customs $customs = null,
        bool $testMode = false
    ) {
        if (!is_null($customs)) {
            $this->type_contents = $customs->type->value;
            $this->signer        = $customs->signer;
            $this->customs       = array_map(
                function (CustomsItem $item): string {
                    return (string) $item;
                },
                $customs->items
            );
        } else {
            $this->type_contents = null;
            $this->signer        = null;
            $this->customs = null;
        }

        $this->test_mode = $testMode ? 'true' : null;

        parent::__construct($credentials, $fromAddress, $toAddress, $service, $dimensions);
    }

    /**
     * @throws Exception
     */
    protected function validate(): void
    {
        if (empty($this->service)) {
            throw new BitcoinPostageInfoException('Service is required when purchasing postage.');
        }

        if ($this->test_mode && 'usps' !== $this->carrier) {
            throw new BitcoinPostageInfoException('Test mode only available for USPS.');
        }

        if (
            $this->from_country !== $this->to_country
            && empty($this->customs)
        ) {
            throw new BitcoinPostageInfoException('Customs information required for international shipments.');
        }
    }
}
