<?php

namespace App\Models;

use App\Enums\UnitStatus;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Unit extends Model implements HasMedia
{
    use HasMediaTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Always eager load these relationships.
     *
     * @var array
     */
    protected $with = ['media', 'periods'];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['periodDate'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function addOnGroups()
    {
        return $this->belongsToMany(AddOnGroup::class)->withPivot('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function discounts()
    {
        return $this->belongsToMany(Discount::class)->withPivot('id');
    }

    /**
     * Get unit's max quantity.
     *
     * @return int
     */
    public function getMaxQuantityAttribute()
    {
        $total = 0;

        foreach ($this->periods as $period) {
            $total += $period->pivot->max_quantity;
        }

        return $total;
    }

    public function getRemainingQuantityAttribute()
    {
        $total = 0;

        foreach ($this->periods as $period) {
            $total += $period->pivot->remaining_quantity;
        }

        return $total;
    }

    /**
     * @return mixed
     */
    public function getSoldAttribute()
    {
        return $this->max_quantity - $this->remaining_quantity;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === (string)UnitStatus::PUBLISHED();
    }

    /**
     * @return mixed
     */
    public static function novaFilterSelectOptions()
    {
        return Cache::remember('nova-filter-unit-options', 3600, function () {
            $units = self::setEagerLoads([])->get(['id', 'name']);

            return $units->mapWithKeys(function ($unit) {
                return [$unit->name => $unit->id];
            })->toArray();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function periods()
    {
        return $this->belongsToMany(Period::class, 'period_unit')
            ->withPivot('id', 'unit_price', 'max_quantity', 'remaining_quantity')
            ->using(PeriodUnit::class)
            ->orderBy('period_unit.unit_price', 'ASC');
    }

    /**
     * Registers media collection for model.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('photo')->singleFile();
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
     * Scope by Published status.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where('status', UnitStatus::PUBLISHED());
    }

    /**
     * Scope by Published / Sold Out status.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopePublishedOrSoldOut($query)
    {
        return $query->whereIn('status', [UnitStatus::PUBLISHED(), UnitStatus::SOLD_OUT()]);
    }
    
    /**
     * @return string|null
     */
    public function getPeriodDateAttribute($query)
    {
        $periods = $this->periods;
        if(count($periods)) {
            return $periods->sortBy('from_date')->first()->from_date->format('d M Y') . ' - ' . $periods->sortByDesc('to_date')->first()->to_date->format('d M Y');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * @return mixed
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->tags();
    }

    /**
     * @return mixed
     */
    public function things()
    {
        return $this->morphToMany(Tag::class, 'taggable')->things();
    }
}
