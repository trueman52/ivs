<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * SpaceStatus enum
 */

/**
 * @method static SpaceStatus PUBLISHED()
 * @method static SpaceStatus UNPUBLISHED()
 * @method static SpaceStatus DRAFT()
 */
class SpaceStatus extends Enum
{
    private const PUBLISHED   = 'published';
    private const UNPUBLISHED = 'unpublished';
    private const DRAFT       = 'draft';
}
