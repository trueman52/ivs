<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * EventPositionType enum
 */

/**
 * @method static EventPositionType TOP()
 * @method static EventPositionType LEFT()
 * @method static EventPositionType RIGHT()
 */
class EventPositionType extends Enum
{
    private const TOP   = 'top';
    private const LEFT  = 'left';
    private const RIGHT = 'right';
}
