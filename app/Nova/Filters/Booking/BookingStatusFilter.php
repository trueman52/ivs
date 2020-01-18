<?php

namespace App\Nova\Filters\Booking;

use App\Enums\BookingStatus;
use App\Models\Space;
use Illuminate\Http\Request;
use Klepak\NovaMultiselectFilter\NovaMultiselectFilter;

class BookingStatusFilter extends NovaMultiselectFilter
{
    /**
     * @var string
     */
    public $name = 'Booking status';

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
        return $query->whereIn('status', $value);
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
        return BookingStatus::toSelectOptions();
    }
}
