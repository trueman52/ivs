<?php

namespace App\Models;

interface ApplicableCoupon
{
    /**
     * Apply coupon calculation
     *
     * @param float $amount
     *
     * @return float
     */
    public function apply(float $amount): float;
}