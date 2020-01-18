<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static RefundType SD_REFUND()
 * @method static RefundType PAYMENT_REFUND()
 */
class RefundType extends Enum
{
    use SelectableOptions;

    private const SD_REFUND = 'SD refund';
    private const PAYMENT_REFUND = 'payment refund';
}
