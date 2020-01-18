<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BoothAssignment extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $dates = ['from', 'to'];

    /**
     * @var array
     */
    protected $casts = [
        'allocated_booths' => 'array'
    ];

    protected $with = [
        'booking.bookingAddOns',
        'booking.adhocItems'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bookingPeriod()
    {
        return $this->belongsTo(BookingPeriod::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function booking()
    {
        return $this->hasOneThrough(
            Booking::class,
            BookingPeriod::class,
            'id',
            'id',
            'booking_period_id',
            'booking_id'
        );
    }

    /**
     * @param $boothNumber
     *
     * @return string
     */
    public function getBoothCode($boothNumber)
    {
        return "{$this->spaceCode}-{$this->unitCode}-{$this->periodCode}-{$boothNumber}";
    }
}
