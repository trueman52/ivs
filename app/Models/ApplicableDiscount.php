<?php

namespace App\Models;

use App\Enums\DiscountRateType;
use Carbon\Carbon;

trait ApplicableDiscount
{
    protected function applyDiscount(float $amount)
    {
        switch ($this->data['rate_type']) {
            case DiscountRateType::FIXED():
                // convert rate amount to cents
                return $amount - ($this->data['rate'] * 100);

                break;
            default:
                // ensure we do not get any decimals
                return (int)round((100 - $this->data['rate']) / 100 * $amount);
        }
    }

    /**
     * Apply limited time discount to calculated periods total.
     *
     * @param float $amount
     *
     * @return float|int
     */
    public function applyLimitedTimeDiscount(float $amount)
    {
        $now   = Carbon::now();
        $start = Carbon::createFromDate($this->data['start_date']);
        $end   = Carbon::createFromDate($this->data['end_date']);

        if (!$now->isBetween($start, $end)) return $amount;

        return $this->applyDiscount($amount);
    }

    /**
     * Apply period quantity discounts to calculated periods total.
     *
     * @param float $amount
     * @param int   $periodsCount
     *
     * @return float|int
     */
    public function applyPeriodDiscount(float $amount, int $periodsCount)
    {
        if ((int)$this->data['no_of_periods'] !== $periodsCount) return $amount;

        return $this->applyDiscount($amount);
    }

    /**
     * Apply unit quantity discounts to calculated periods total.
     *
     * @param float $amount
     * @param int   $quantity
     *
     * @return float|int
     */
    public function applyQuantityDiscount(float $amount, int $quantity)
    {
        if ((int)$this->data['no_of_units'] !== $quantity) return $amount;

        return $this->applyDiscount($amount);
    }

    /**
     * This always ensures we get an array.
     * Somehow, there are cases whereby when we depend on $casts
     * to cast it to array, we don't get back arrays.
     *
     * @param $value
     *
     * @return mixed
     */
    public function getDataAttribute($value)
    {
        if (is_string($value)) return json_decode($value, true);

        return $value;
    }

    public function getDiscountValueAttribute()
    {
        switch ($this->data['rate_type']) {
            case DiscountRateType::PERCENTAGE():
                return "{$this->data['rate']}%";
            default:
                return "\${$this->data['rate']}";
        }
    }
}