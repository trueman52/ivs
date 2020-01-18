<?php

namespace App\Nova\Filters;

use App\Enums\SpaceStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class SpaceFilter extends Filter
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
            ucfirst(SpaceStatus::DRAFT()->getValue())       => SpaceStatus::DRAFT(),
            ucfirst(SpaceStatus::PUBLISHED()->getValue())   => SpaceStatus::PUBLISHED(),
            ucfirst(SpaceStatus::UNPUBLISHED()->getValue()) => SpaceStatus::UNPUBLISHED(),
        ];
    }
}
