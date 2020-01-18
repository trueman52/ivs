<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class AddOn extends Model implements HasMedia
{
    use HasMediaTrait,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['mediaFullUrl'];

    /**
     * Registers media collection for model.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    /**
     * Get a single media Image FullUrl from the collection.
     *
     * @return string|null
     */
    public function getMediaFullUrlAttribute()
    {
        $photo = $this->media()->where('collection_name', 'photo')->first();

        return $photo ? $photo->getFullUrl() : null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function addOnGroups()
    {
        return $this->belongsToMany(AddOnGroup::class, 'add_on_add_on_group')->withPivot('id', 'min', 'max', 'cost_per_unit');
    }
}
