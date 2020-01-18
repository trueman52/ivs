<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * DiscountStatus enum
 */

/**
 * @method static DiscountStatus INACTIVE()
 * @method static DiscountStatus ACTIVE()
 */
class DiscountStatus extends Enum
{
    private const INACTIVE = 'inactive';
    private const ACTIVE   = 'active';
}
