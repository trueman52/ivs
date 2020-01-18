<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * AddOnGroupStatus enum
 */

/**
 * @method static AddOnGroupStatus INACTIVE()
 * @method static AddOnGroupStatus ACTIVE()
 */
class AddOnGroupStatus extends Enum
{
    private const INACTIVE = 'inactive';
    private const ACTIVE   = 'active';
}
