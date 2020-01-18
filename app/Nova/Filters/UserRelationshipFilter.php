<?php

namespace App\Nova\Filters;

use App\Models\User;
use Illuminate\Http\Request;
use Klepak\NovaMultiselectFilter\NovaMultiselectFilter;

class UserRelationshipFilter extends NovaMultiselectFilter
{
    /**
     * @var string
     */
    public $name = 'User';

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
        return $query->whereIn('user_id', $value);
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
        return User::novaFilterSelectOptions();
    }
}
