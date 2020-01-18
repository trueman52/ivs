<?php

namespace App\Models;

use App\Exceptions\UnableToCreateBookingException;
use App\Helpers\Money;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    use SoftDeletes, HasBillingDetails;
        
    
    /**
     * @var array
     */
    protected $appends = [
        'spaceName',
        'unitName',
        'unitImage',
        'outstanding',
        'paid',
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Adhoc items form the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adhocItems()
    {
        return $this->hasMany(AdhocItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function application()
    {
        return $this->hasOne(Application::class);
    }

    /**
     * Add ons information for the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingAddOns()
    {
        return $this->hasMany(BookingAddOn::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingDiscounts()
    {
        return $this->hasMany(BookingDiscount::class);
    }

    /**
     * Add ons information for the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingPeriods()
    {
        return $this->hasMany(BookingPeriod::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function boothAssignments()
    {
        return $this->hasManyThrough(BoothAssignment::class, BookingPeriod::class);
    }

    /**
     * Customer that owns this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The customer's details for this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customerDetail()
    {
        return $this->hasOne(CustomerDetail::class);
    }

    /**
     * Get deposit amount in dollars.
     *
     * @return string
     */
    protected function getDepositInDollarsAttribute()
    {
        return Money::toDollars($this->deposit);
    }

    /**
     * Get grand total amount in dollars.
     *
     * @return string
     */
    protected function getGrandTotalInDollarsAttribute()
    {
        return Money::toDollars($this->grandTotal);
    }

    /**
     * @return mixed
     */
    protected function getGstAttribute()
    {
        if (!isset($this->data['calculations'])) return null;

        return $this->data['calculations']['gst']['amount'];
    }

    /**
     * The outstanding amount for the order.
     *
     * @return mixed
     */
    public function getOutstandingAttribute()
    {
        return Money::toDollars($this->grandTotal - $this->paid);
    }

    /**
     * Amount paid attribute
     *
     * @return mixed
     */
    public function getPaidAttribute()
    {
        return (int)$this->payments()->sum('amount');
    }

    /**
     * Get paid amount in dollars.
     *
     * @return string
     */
    protected function getPaidInDollarsAttribute()
    {
        return Money::toDollars($this->paid);
    }

    /**
     * Get space's name attribute.
     *
     * @return mixed
     */
    public function getSpaceNameAttribute()
    {
        return $this->space->name;
    }

    /**
     * Get unit's name attribute.
     *
     * @return mixed
     */
    public function getUnitNameAttribute()
    {
        return $this->unit->name;
    }
    
    /**
     * Get unit's image attribute.
     *
     * @return mixed
     */
    public function getUnitImageAttribute()
    {
        $media = $this->unit->media()->first();
        if($media) {
            return $media->getFullUrl();
        }
    }

    /**
     * Checks if a booking is associated to a booking.
     *
     * @param \App\Models\Coupon $coupon
     *
     * @return bool
     */
    public function isUsingCoupon(Coupon $coupon)
    {
        return (bool)$this->usedCoupon()->where('coupon_id', $coupon->id)->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * Remove the coupon from that was used from this booking.
     */
    public function removeUsedCoupon()
    {
        $this->usedCoupon->coupon->unused();

        $this->usedCoupon->delete();
    }

    /**
     * Save the unit's add-ons details
     *
     * @param \Illuminate\Support\Collection $groupedAddOns - Containing Unit's grouped add-ons.
     */
    public function saveAddOns(Collection $groupedAddOns)
    {
        $bookingAddOns = $groupedAddOns->map(function (GroupedAddOn $addOn) {
//            if (!$addOn->isQuantityRequirementsMet($addOn->quantity)) {
//                throw new UnableToCreateBookingException("Add-ons quantity requirement unmet. Min: {$addOn->min}, Max: {$addOn->max}, Provided: {$addOn->quantity}");
//            }

            return new BookingAddOn([
                'add_on_add_on_group_id' => $addOn->id,
                'quantity'               => $addOn->quantity,
                'purchased_at'           => $addOn->costPerUnit,
            ]);
        });

        $this->bookingAddOns()->saveMany($bookingAddOns);
    }

    /**
     * Save the adhoc items.
     *
     * @param array $adhocItems
     */
    public function saveAdhocItems(array $adhocItems)
    {
        $items = [];

        foreach ($adhocItems as $item) {
            $items[] = new AdhocItem([
                'name'     => $item['name'],
                'quantity' => $item['quantity'],
                'amount'   => $item['amount'],
            ]);
        }

        $this->adhocItems()->saveMany($items);
    }

    /**
     * Save the discounts.
     *
     * @param \Illuminate\Support\Collection $discounts
     */
    public function saveDiscounts(Collection $discounts)
    {
        $bookingDiscounts = $discounts->map(function (Discount $discount) {
            return new BookingDiscount([
                'discount_id' => $discount->id,
                'name'        => $discount->name,
                'type'        => $discount->type,
                'data'        => json_encode($discount->data),
            ]);
        });

        $this->bookingPeriods()->saveMany($bookingDiscounts);
    }

    /**
     * Save the unit period's to this booking.
     *
     * @param \Illuminate\Support\Collection $periods  - Collection of Unit's App\Models\Period.
     * @param int                            $quantity - Quantity of each period booked.
     */
    public function savePeriods(Collection $periods, int $quantity)
    {
        $bookingPeriods = $periods->map(function (Period $period) use ($quantity) {
            if (!$period->isQuantityRequirementsMet($quantity)) {
                throw new UnableToCreateBookingException("Periods quantity requirement unmet. Remaining: {$period->pivot->remaining_quantity}, Provided: {$quantity}");
            }

            return new BookingPeriod([
                'period_id'    => $period->id,
                'quantity'     => $quantity,
                'purchased_at' => $period->pivot->unit_price,
            ]);
        });

        if ($bookingPeriods->isEmpty()) return;

        // Get the pivot's table id so that we can perform updates through DB statement.
        $periodUnitIds = $periods->pluck('pivot.id')->toArray();

        // Decrease all period_unit quantity.
        DB::table('period_unit')
            ->whereIn('id', $periodUnitIds)
            ->decrement('remaining_quantity', $quantity);

        // Store the booking periods.
        $this->bookingPeriods()->saveMany($bookingPeriods);
    }

    /**
     * Scope bookings that are created two dates.
     *
     * @param       $query
     * @param array $dates - containing start and end date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBetweenDates($query, array $dates)
    {
        return $query->whereDate('created_at', '>=', $dates['start'])
            ->whereDate('created_at', '<=', $dates['end']);
    }

    /**
     * The space this booking belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * The booked unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * The used coupon details for this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usedCoupon()
    {
        return $this->hasOne(UsedCoupon::class);
    }
}
