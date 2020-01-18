<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BookingPeriod extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function boothAssignments()
    {
        return $this->hasMany(BoothAssignment::class);
    }

    /**
     * @return mixed
     */
    public function deleteBoothAssignments()
    {
        return $this->boothAssignments()->delete();
    }
}
