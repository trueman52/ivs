<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * UserStatus enum
 */

/**
 * @method static UserStatus INACTIVE()
 * @method static UserStatus ACTIVE()
 */
class UserStatus extends Enum
{
    private const INACTIVE = 'inactive';
    private const ACTIVE   = 'active';
}
