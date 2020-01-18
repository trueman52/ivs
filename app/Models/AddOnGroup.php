<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AddOnGroup extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function addOns()
    {
        return $this->belongsToMany(AddOn::class)
                    ->withPivot('id', 'min', 'max', 'cost_per_unit');
    }

    public function groupedAddOns()
    {
        return $this->hasMany(GroupedAddOn::class);
    }
}
