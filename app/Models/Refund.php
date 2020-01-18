<?php

namespace App\Models;

use App\Enums\RefundStatus;

class Refund extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function coupon()
    {
        return $this->hasOne(Coupon::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function customer()
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'id',
            'id',
            'booking_id',
            'user_id'
        );
    }

    /**
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        if (!$value) $this->attributes['status'] = RefundStatus::PENDING_APPROVAL();
    }
}
