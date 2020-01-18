<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * DiscountType Type enum
 */

/**
 * @method static DiscountType QUANTITY()
 * @method static DiscountType PERIOD()
 * @method static DiscountType LIMITED_TIME()
 */
class DiscountType extends Enum
{
    private const QUANTITY     = 'quantity';
    private const PERIOD       = 'period';
    private const LIMITED_TIME = 'limited time';
}
