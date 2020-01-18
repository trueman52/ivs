<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * DiscountRateType Type enum
 */

/**
 * @method static DiscountRateType FIXED()
 * @method static DiscountRateType PERCENTAGE()
 */
class DiscountRateType extends Enum
{
    private const FIXED      = 'fixed';
    private const PERCENTAGE = 'percentage';
}
