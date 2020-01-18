<?php

namespace App\Models;
use Carbon\Carbon;

class Period extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $dates = [
        'from_date',
        'to_date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['period_available'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'period_unit')
            ->withPivot('id', 'unit_price', 'max_quantity', 'remaining_quantity');
    }

    /**
     * @return string
     */
    public function getPeriodDetailAttribute()
    {
        return $this->code . ' (' . $this->from_date->format('d-m-Y') . ' To ' . $this->to_date->format('d-m-Y') . ')';
    }

    /**
     * @return string
     */
    public function getDateDetailAttribute()
    {
        return $this->from_date->format('d-m-Y') . ' to ' . $this->to_date->format('d-m-Y');
    }
    
    /**
     * @return bool
     */
    public function getPeriodAvailableAttribute()
    {
        return $this->to_date->gte(Carbon::now());
    }
    
    /**
     * Checks if there are sufficient quantity.
     *
     * @param int $quantity
     *
     * @return boolean
     */
    public function isQuantityRequirementsMet(int $quantity)
    {
        return $quantity <= $this->pivot->remaining_quantity;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingPeriods()
    {
        return $this->hasMany(BookingPeriod::class);
    }
}
