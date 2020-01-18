<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PaymentMethod CARD()
 * @method static PaymentMethod OFFLINE()
 */
class PaymentMethod extends Enum
{
    use SelectableOptions;

    private const CARD = 'credit/debit card';
    private const OFFLINE = 'offline payment';
}
