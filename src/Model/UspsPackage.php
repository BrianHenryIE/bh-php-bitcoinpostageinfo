<?php

namespace BrianHenryIE\BitcoinPostageInfo\Model;

enum UspsPackage: String
{
    /**
     *  Must be a flat surface with a maximum weight of 3.5oz.
     * Minimum dimensions: 3 1/2" x 5 1/2'. Maximum dimensions: 4 1/4" x 6".
     */
    case LETTER = 'Letter';

    /**
     * Maximum size is 108" in combined length and girth (distance around the thickest part).
     * Contents must weigh less than 70 lbs.
     */
    case PARCEL = 'Parcel';

    /**
     * Uses pre-purchased 12-1/2" x 9-1/2" package.
     * Contents must weigh less than 70 lbs (4 lbs for international).
     */
    case FLAT_RATE_ENVELOPE = 'FlatRateEnvelope';

    /**
     * Uses pre-purchased 15" x 9 1/2" package.
     * Contents must weigh less than 70 lbs (4 lbs for international).
     */
    case FLAT_RATE_LEGAL_ENVELOPE = 'FlatRateLegalEnvelope';

    /**
     * Uses pre-purchased 8 5/8" x 5 3/8" x 1 5/8" package.
     * Contents must weigh less than 70 lbs.
     */
    case SMALL_FLAT_RATE_BOX = 'SmallFlatRateBox';

    /**
     * Uses pre-purchased 11" x 8 1/2" x 5 1/2" package.
     * Contents must weigh less than 70 lbs.
     */
    case MEDIUM_FLAT_RATE_BOX = 'MediumFlatRateBox';

    /**
     * Uses pre-purchased 12" x 12" x 5 1/2" package.
     * Contents must weigh less than 70 lbs.
     */
    case LARGE_FLAT_RATE_BOX = 'LargeFlatRateBox';
}
