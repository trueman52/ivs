<?php

namespace App\Nova\Filters;

use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class UserFilter extends Filter
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
            ucfirst(UserStatus::INACTIVE()->getValue()) => UserStatus::INACTIVE(),
            ucfirst(UserStatus::ACTIVE()->getValue())   => UserStatus::ACTIVE(),
        ];
    }
}
