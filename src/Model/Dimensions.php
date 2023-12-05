<?php

/**
 * Inches.
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model;

class Dimensions
{
    /**
     * @param int $weightLbs      Pounds of package weight (submit remainder as ounces in `weightOz` parameter)
     * @param float $weightOz     Ounces remainder of package weight (not full weight in ounces)
     * @param float $heightInches Inches of package height
     * @param float $widthInches  Inches of package width
     * @param float $depthInches  Inches of package depth/length
     */
    public function __construct(
        public readonly int $weightLbs,
        public readonly float $weightOz,
        public readonly float $heightInches,
        public readonly float $widthInches,
        public readonly float $depthInches,
    ) {
    }
}
