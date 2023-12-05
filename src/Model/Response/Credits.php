<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model\Response;

class Credits
{
    public function __construct(
        /**
         * @var string $credits Account balance in USD.
         */
        public readonly string $credits
    ) {
    }
}
