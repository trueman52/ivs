<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * CustomerStatus enum
 */

/**
 * @method static CustomerStatus INACTIVE()
 * @method static CustomerStatus ACTIVE()
 * @method static CustomerStatus EMAIL_UNVERIFIED()
 */
class CustomerStatus extends Enum
{
    private const INACTIVE         = 'inactive';
    private const ACTIVE           = 'active';
    private const EMAIL_UNVERIFIED = 'email unverified';
}
