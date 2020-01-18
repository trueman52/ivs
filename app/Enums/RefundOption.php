<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static RefundOption COUPON()
 * @method static RefundOption CASH()
 */
class RefundOption extends Enum
{
    use SelectableOptions;

    private const COUPON = 'coupon';
    private const CASH = 'cash';
}
