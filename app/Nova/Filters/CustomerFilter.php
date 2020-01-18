<?php

namespace App\Nova\Filters;

use App\Enums\CustomerStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class CustomerFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $name = 'Status';

    public $component = 'select-filter';

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
        return $query->where('status', $value);
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
        return [
            ucfirst(CustomerStatus::INACTIVE()->getValue())         => CustomerStatus::INACTIVE(),
            ucfirst(CustomerStatus::ACTIVE()->getValue())           => CustomerStatus::ACTIVE(),
            ucfirst(CustomerStatus::EMAIL_UNVERIFIED()->getValue()) => CustomerStatus::EMAIL_UNVERIFIED(),
        ];
    }
}
