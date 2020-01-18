<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static BusinessAgeSize ON_HOLD()
 * @method static BusinessAgeSize IN_REVIEW()
 * @method static BusinessAgeSize CONFIRMED()
 * @method static BusinessAgeSize DECLINED()
 * @method static BusinessAgeSize COMPLETED()
 * @method static BusinessAgeSize CANCELLED()
 */
class BookingStatus extends Enum
{
    use SelectableOptions;
    
    private const ON_HOLD = 'on-hold';
    private const IN_REVIEW = 'in review';
    private const CONFIRMED = 'confirmed';
    private const DECLINED = 'declined';
    private const COMPLETED = 'completed';
    private const CANCELLED = 'cancelled';
}
