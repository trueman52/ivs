<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessDetail extends Model
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
    protected $casts = [
        'urls' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['website', 'facebook', 'instagram'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function businessCharacteristics()
    {
        return $this->morphToMany(Tag::class, 'taggable')->characteristics();
    }

    /**
     * @return string|null
     */
    public function getWebsiteAttribute()
    {
        return $this->urls['website'];
    }
    
    /**
     * @return string|null
     */
    public function getFacebookAttribute()
    {
        return $this->urls['facebook'];
    }
    
    /**
     * @return string|null
     */
    public function getInstagramAttribute()
    {
        return $this->urls['instagram'];
    }
}
