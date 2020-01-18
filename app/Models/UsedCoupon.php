<?php

namespace App\Models;

use App\Enums\DiscountRateType;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsedCoupon extends Model implements ApplicableCoupon
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array'
    ];

    /**
     * The coupon that was used for the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * The booking this coupon was used on.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getCouponDataAttribute()
    {
        return $this->data['coupon_details']['data'];
    }

    /**
     * Recalculate coupon discount to amount.
     *
     * @param float $amount
     *
     * @return float
     */
    public function apply(float $amount): float
    {
        switch ($this->coupon_data['rate_type']) {
            case DiscountRateType::FIXED():
                return $amount - ($this->coupon_data['rate'] * 100); // convert rate amount to cents

                break;
            default:
                return (100 - $this->coupon_data['rate']) / 100 * $amount;
        }
    }
}
