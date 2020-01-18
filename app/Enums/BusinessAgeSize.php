<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * BusinessAgeSize enum
 */

/**
 * @method static BusinessAgeSize AGE_UNDER_1()
 * @method static BusinessAgeSize AGE_2_TO_5()
 * @method static BusinessAgeSize AGE_ABOVE_5()
 */
class BusinessAgeSize extends Enum
{
    private const AGE_UNDER_1_YEAR  = 'under 1 year';
    private const AGE_2_TO_5_YEARS  = '2 to 5 years';
    private const AGE_ABOVE_5_YEARS = 'above 5 years';
}
