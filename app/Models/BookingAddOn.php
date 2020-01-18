<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BookingAddOn extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /**
     * The general add-on details this booking add-on owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function addOn()
    {
        return $this->hasOneThrough(
            AddOn::class,
            GroupedAddOn::class,
            'id',
            'id',
            'add_on_add_on_group_id',
            'add_on_id'
        );
    }

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
    public function groupedAddOn()
    {
        return $this->hasOne(GroupedAddOn::class, 'id', 'add_on_add_on_group_id');
    }
}
