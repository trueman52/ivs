<?php

namespace App\Models;

use App\Enums\SpaceStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Space extends Model implements HasMedia
{
    use HasMediaTrait,
        Filterable,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'highlights' => 'array',
        'urls'       => 'array',
    ];

    protected $dates = [
        'booking_closing_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['mediaFirstFullUrl', 'facebookUrl', 'spaceUrl', 'instagramUrl'];

    /**
     * Always eager load these relationships.
     *
     * @var array
     */
    protected $with = ['media', 'address', 'tags'];

    /**
     * Address sections.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coordinators()
    {
        return $this->belongsToMany(User::class, 'coordinations')
            ->as('coordinators')
            ->withPivot('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function favourite()
    {
        return $this->belongsTo(Favourite::class);
    }

    /**
     * @return mixed
     */
    public function features()
    {
        return $this->morphToMany(Tag::class, 'taggable')->features();
    }

    /**
     * @return string|null
     */
    public function getCountryAttribute()
    {
        return $this->address ? $this->address->country : null;
    }

    /**
     * @return string|null
     */
    public function getFacebookUrlAttribute()
    {
        return isset($this->urls['facebook_url']) ? $this->urls['facebook_url'] : null;
    }

    /**
     * @return string|null
     */
    public function getInstagramUrlAttribute()
    {
        return isset($this->urls['instagram_url']) ? $this->urls['instagram_url'] : null;
    }

    /**
     * Get a single media Image FullUrl from the collection.
     *
     * @return string|null
     */
    public function getMediaFirstFullUrlAttribute()
    {
        $banner = $this->media()->where('collection_name', 'banners')->first();

        return $banner ? $banner->getFullUrl() : null;
    }

    /**
     * @return string|null
     */
    public function getPostalCodeAttribute()
    {
        return $this->address ? $this->address->postal_code : null;
    }

    /**
     * @return array|null
     */
    public function getSocialUrlAttribute()
    {
        return json_decode($this->urls, true);
    }

    /**
     * @return string|null
     */
    public function getSpaceUrlAttribute()
    {
        return isset($this->urls['space_url']) ? $this->urls['space_url'] : null;
    }

    /**
     * @return string|null
     */
    public function getStreetAttribute()
    {
        return $this->address ? $this->address->street : null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inCharge()
    {
        return $this->belongsTo(User::class, 'in_charge');
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === (string)SpaceStatus::PUBLISHED();
    }

    /**
     * @return mixed
     */
    public static function novaFilterSelectOptions()
    {
        return Cache::remember('nova-filter-space-options', 3600, function () {
            $spaces = self::setEagerLoads([])->get(['id', 'name']);

            return $spaces->mapWithKeys(function ($space) {
                return [$space->name => $space->id];
            })->toArray();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    /**
     * Registers media collection for model.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('vendor_kit')->singleFile();
        $this->addMediaCollection('banner');
    }

    /**
     * Scope by created at Order.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Scope a query to only include spaces owned by a user.
     *
     * @param $query
     * @param $ownerId - int|string
     *
     * @return mixed
     */
    public function scopeOwnedBy($query, $ownerId)
    {
        return $query->whereHas('coordinators', function ($q) use ($ownerId) {
            $q->where('user_id', $ownerId);
        });
    }

    /**
     * Scope by Published status.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where('status', SpaceStatus::PUBLISHED());
    }

    /**
     * Scope a query to only include space with name.
     *
     * @param $query
     * @param $name - string
     *
     * @return mixed
     */
    public function scopeSpaceName($query, $name)
    {
        if (!$name) return $query;
        return $query->where('name', 'like', "%{$name}%")
            ->orWhere('code', 'like', "%{$name}%");
    }

    /**
     * @return mixed
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->tags();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
