<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * CouponStatus enum
 */

/**
 * @method static CouponStatus INACTIVE()
 * @method static CouponStatus ACTIVE()
 */
class CouponStatus extends Enum
{
    private const INACTIVE = 'inactive';
    private const ACTIVE   = 'active';
}
