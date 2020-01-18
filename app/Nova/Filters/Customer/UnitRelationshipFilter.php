<?php

namespace App\Nova\Filters\Customer;

use App\Models\Unit;
use Illuminate\Http\Request;
use Klepak\NovaMultiselectFilter\NovaMultiselectFilter;

class UnitRelationshipFilter extends NovaMultiselectFilter
{
    /**
     * @var string
     */
    public $name = 'Unit';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request              $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->whereHas('bookings', function ($query) use ($value) {
            $query->where('unit_id', $value);
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function options(Request $request)
    {
        return Unit::novaFilterSelectOptions();
    }
}
