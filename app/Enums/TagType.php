<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * TagType enum
 */

/**
 * @method static TagType TAG()
 * @method static TagType THINGS_TO_NOTE()
 * @method static TagType NOTABLE_FEATURE()
 * @method static TagType BUSINESS_CHARACTERISTICS()
 */
class TagType extends Enum
{
    private const TAG                      = 'tag';
    private const THINGS_TO_NOTE           = 'things to note';
    private const NOTABLE_FEATURE          = 'notable feature';
    private const BUSINESS_CHARACTERISTICS = 'business characteristic';
}
