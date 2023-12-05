<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model;

enum FedExPackage: String
{
    /**
     * Minumum size 6" x 4" x 1". Minimum weight 16 oz.
     */
    case CUSTOM = 'custom';

    /**
     * Uses pre-purchased 12.25" x 10.9" x 1.5" or 8.75" x 2.63" x 11.25" package.
     * Contents must weigh no more than 20 lbs.
     */
    case BOX_SMALL = 'fedex_box_small';

    /**
     * Uses pre-purchased 13.25" x 11.5" x 2.38" or 8.75" x 4.38" x 11.25" package.
     * Contents must weigh no more than 20 lbs.
     */
    case BOX_MEDIUM = 'fedex_box_medium';

    /**
     * Uses pre-purchased 17.88" x 12.38" x 3" or 8.75" x 7.75" x 11.25" package.
     * Contents must weigh no more than 20 lbs.
     */
    case BOX_LARGE = 'fedex_box_large';
}
