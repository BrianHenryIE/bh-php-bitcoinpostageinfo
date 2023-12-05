<?php

/**
 *
 * Type of Contents: Documents, Gift, Merchandise, Returned Goods, Sample
 */

namespace BrianHenryIE\BitcoinPostageInfo\Model;

enum CustomsContentsType: String
{
    case DOCUMENTS = 'Documents';
    case GIFT = 'Gift';
    case MERCHANDISE = 'Merchandise';
    case RETURNED_GOODS = 'Returned Goods';
    case SAMPLE = 'Sample';
}
