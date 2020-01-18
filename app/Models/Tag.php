<?php

namespace App\Models;

use App\Enums\TagType;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCharacteristics($query)
    {
        return $query->where('type', TagType::BUSINESS_CHARACTERISTICS());
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeFeatures($query)
    {
        return $query->where('type', TagType::NOTABLE_FEATURE());
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeTags($query)
    {
        return $query->where('type', TagType::TAG());
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeThings($query)
    {
        return $query->where('type', TagType::THINGS_TO_NOTE());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function businessDetail()
    {
        return $this->morphedByMany(BusinessDetail::class, 'taggable');
    }
}
