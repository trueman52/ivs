<?php

namespace App\Nova\Filters;

use App\Enums\DiscountType;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class DiscountTypeFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $name = 'Type';

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
        return $query->where('type', $value);
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
            ucfirst(DiscountType::QUANTITY()->getValue())     => DiscountType::QUANTITY(),
            ucfirst(DiscountType::PERIOD()->getValue())       => DiscountType::PERIOD(),
            ucfirst(DiscountType::LIMITED_TIME()->getValue()) => DiscountType::LIMITED_TIME(),
        ];
    }
}
