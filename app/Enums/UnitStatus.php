<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * UnitStatus enum
 */

/**
 * @method static UnitStatus PUBLISHED()
 * @method static UnitStatus UNPUBLISHED()
 * @method static UnitStatus SOLD_OUT()
 */
class UnitStatus extends Enum
{
    private const PUBLISHED    = 'published';
    private const UNPUBLISHED  = 'unpublished';
    private const SOLD_OUT     = 'sold out';
}
