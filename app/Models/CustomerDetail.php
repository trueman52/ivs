<?php

namespace App\Models;

class CustomerDetail extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $appends = [
        'name',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'contact_number' => 'array',
    ];

    /**
     * Get customer's full name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return mixed
     */
    public function getCustomerStatusAttribute()
    {
        return $this->booking->customer->status;
    }
}
