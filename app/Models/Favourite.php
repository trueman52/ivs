<?php

namespace App\Models;

use Eloquence\Behaviours\CamelCasing;

class Favourite extends Model
{
    use CamelCasing;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $with = ['space'];

    /**
     * Space which this favourite belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * Which user owns this favourite.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
