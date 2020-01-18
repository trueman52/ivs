<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
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
    protected $with = ['address'];

    /**
     * @var array
     */
    protected $casts = [
        'contact_number' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['country_code', 'phone_number'];

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string|null
     */
    public function getCountryCodeAttribute()
    {
        return $this->contact_number['code'];
    }
    
    /**
     * @return string|null
     */
    public function getPhoneNumberAttribute()
    {
        return $this->contact_number['number'];
    }

    /**
     * Get the concatenated contact number.
     *
     * @return string
     */
    public function getFullContactNumberAttribute()
    {
        if (!$this->contact_number) return '';

        return "{$this->contact_number['code']}{$this->contact_number['number']}";
    }

}
