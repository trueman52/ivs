<?php

namespace App\Enums;

trait SelectableOptions
{
    /**
     * Return array for select options.
     *
     * @return array|false
     */
    public static function toSelectOptions()
    {
        $arr = self::toArray();

        return array_combine($arr, $arr);
    }
}