<?php

namespace App\Helpers;

class Money
{
    /**
     * Converts '12,345.67' to 1234567
     *
     * @param string $amount
     *
     * @return int
     */
    public static function toCents(string $amount): int
    {
        return (int)str_replace('.', '', str_replace(',', '', $amount));
    }

    /**
     * Converts '1234567' to 12,345.67
     *
     * @param int $amount
     * @param int $decimal
     *
     * @return string
     */
    public static function toDollars(int $amount, int $decimal = 2): string
    {
        return number_format($amount / 100, $decimal);
    }
}