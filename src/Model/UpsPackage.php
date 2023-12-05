<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model;

enum UpsPackage: String
{
    /**
     * Custom size box or package
     */
    case CUSTOM = 'custom';

    /**
     * Uses pre-purchased 13" x 11" x 2"
     */
    case BOX_SMALL = 'ups_box_small';

    /**
     * Uses pre-purchased 15" x 11" x 3"
     */
    case BOX_MEDIUM = 'ups_box_medium';

    /**
     * Uses pre-purchased 18" x 13" x 3"
     */
    case BOX_LARGE = 'ups_box_large';
}
