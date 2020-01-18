<?php

namespace App\Models;

use Eloquence\Behaviours\CamelCasing;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupedAddOn extends Pivot
{
    use CamelCasing;

    /**
     * This is a transient property which will help keep track of
     * the quantity when making a booking.
     *
     * @var int
     */
    public $quantity = 0;

    /**
     * @var array
     */
    protected $appends = ['name'];

    /**
     * @var string
     */
    protected $table = 'add_on_add_on_group';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function addOn()
    {
        return $this->belongsTo(AddOn::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function addOnGroup()
    {
        return $this->belongsTo(AddOnGroup::class);
    }

    /**
     * @return mixed
     */
    public function getNameAttribute()
    {
        return $this->addOn->frontendName;
    }

    /**
     * Checks if quantity is between min and max
     *
     * @param int $quantity
     *
     * @return boolean
     */
    public function isQuantityRequirementsMet(int $quantity)
    {
        return $quantity >= $this->min && $quantity <= $this->max;
    }
}
