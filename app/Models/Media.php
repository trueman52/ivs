<?php

namespace App\Models;

use Eloquence\Behaviours\CamelCasing;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use CamelCasing,
        SoftDeletes;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['fullUrl'];

    /**
     * Access the full url as a property.
     *
     * @return string
     */
    public function getFullUrlAttribute()
    {
        return $this->getFullUrl();
    }

}
