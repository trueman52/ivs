<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * PeriodStatus enum
 */

/**
 * @method static PeriodStatus AVAILABLE()
 * @method static PeriodStatus NOT_AVAILABLE()
 */
class PeriodStatus extends Enum
{
    private const AVAILABLE     = 'available';
    private const NOT_AVAILABLE = 'not available';
}
