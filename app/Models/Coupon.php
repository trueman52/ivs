<?php

namespace App\Models;

use App\Enums\CouponStatus;
use App\Enums\DiscountRateType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model implements ApplicableCoupon
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $dates = [
        'valid_from',
        'valid_to',
    ];

    /**
     * Apply coupon discount to amount.
     *
     * @param float $amount
     *
     * @return float
     */
    public function apply(float $amount): float
    {
        switch ($this->data['rate_type']) {
            case DiscountRateType::FIXED():
                // convert rate amount to cents
                $amount -= ($this->data['rate'] * 100);

                break;
            default:
                // ensure we do not get any decimals when we are already dealing with cents.
                $amount = (int)round((100 - $this->data['rate']) / 100 * $amount);
        }

        return $amount < 0 ? 0 : $amount;
    }

    /**
     * The user that created this coupon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user which this coupon is gifted to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'created_for');
    }

    /**
     * Get data as an array. Not using '$casts' as there are occasions
     * whereby it doesn't cast it as an array like how we expect.
     *
     * @param $value
     *
     * @return mixed
     */
    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * The number of quantity for this coupon.
     */
    public function getQuantityAttribute()
    {
        if (!isset($this->data['total_quota'])) return;

        return $this->data['total_quota'];
    }

    /**
     * Checks if the coupon is within usable date range.
     *
     * @return bool
     * @throws \Exception
     */
    public function isOngoing()
    {
        $to  = $this->validTo;
        $now = new Carbon();

        return $now->between($this->validFrom, $to->addDay());
    }

    /**
     * Checks if the coupon has enough quantity to be used.
     *
     * @return bool
     */
    public function isSufficient()
    {
        return $this->quantity > 0;
    }

    /**
     * Checks if the given coupon is usable.
     *
     * @return bool
     * @throws \Exception
     */
    public function isUsable()
    {
        if (!$this->isOngoing()) return false;

        if (!$this->isSufficient()) return false;

        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', (string)CouponStatus::ACTIVE());
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeInactive($query)
    {
        return $query->where('status', (string)CouponStatus::INACTIVE());
    }

    /**
     * Scope by coupons that falls out of our 'valid_from'
     * and 'valid_to' date range.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotOngoing($query)
    {
        $now = Carbon::now();

        return $query->whereDate('valid_from', '>', $now)->OrWhereDate('valid_to', '<', $now);
    }

    /**
     * Scope coupons that has date ranges between 'valid_from'
     * and 'valid_to'.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeOngoing($query)
    {
        $now = Carbon::now();

        return $query->whereDate('valid_from', '<=', $now)->whereDate('valid_to', '>=', $now);
    }

    /**
     * Scope coupons that hasn't been used up.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeSufficientQuantity($query)
    {
        return $query->where('data->total_quota', '>', 0);
    }

    /**
     * Scope coupons by its validity criteria.
     * - whether or not its ongoing
     * - whether the coupon is still active
     * - whether the quantity of the coupon is not 0.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeValid($query)
    {
        return $query->ongoing()
            ->sufficientQuantity()
            ->active();
    }

    /**
     * Automatically encodes it into a json.
     *
     * @param $value
     *
     * @return void
     */
    public function setDataAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['data'] = json_encode($value);

            return;
        }

        $this->attributes['data'] = $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id');
    }

    /**
     * Reduce the quota by 1 after it has been used.
     */
    public function unused()
    {
        // A workaround to edit array properties.
        $data = $this->data;
        $data['total_quota']++;
        $this->data = $data;

        $this->save();
    }

    /**
     * Reduce the quota by 1 after it has been used.
     */
    public function used()
    {
        // A workaround to edit array properties.
        $data = $this->data;
        $data['total_quota']--;
        $this->data = $data;

        $this->save();
    }

    /**
     * The used coupon information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usedCoupon()
    {
        return $this->hasMany(UsedCoupon::class);
    }

    /**
     * Stores information on which booking this is used on.
     *
     * @param \App\Models\Booking $booking
     *
     * @return void
     */
    public function usedOn(Booking $booking)
    {
        UsedCoupon::create([
            'coupon_id'  => $this->id,
            'booking_id' => $booking->id,
            'data'       => ['coupon_details' => $this],
        ]);

        $this->used();
    }
}
