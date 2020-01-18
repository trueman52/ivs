<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static RefundStatus PENDING_APPROVAL()
 * @method static RefundStatus PENDING_BANK_DETAILS()
 * @method static RefundStatus PENDING_FINANCE()
 * @method static RefundStatus REFUNDED()
 * @method static RefundStatus DECLINED()
 * @method static RefundStatus EXPIRED()
 * @method static RefundStatus VOIDED()
 */
class RefundStatus extends Enum
{
    use SelectableOptions;

    private const PENDING_APPROVAL = "pending approval";
    private const PENDING_BANK_DETAILS = "pending bank details";
    private const PENDING_FINANCE = "pending finance";
    private const REFUNDED = "refunded";
    private const DECLINED = "declined";
    private const EXPIRED = "expired";
    private const VOIDED = "voided";
}
