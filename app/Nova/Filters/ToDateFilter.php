<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class ToDateFilter extends DateFilter
{
    /**
     * The column that should be filtered on.
     *
     * @var string
     */
    protected $column;

    /**
     * DateFromFilter constructor.
     *
     * @param string $column
     * @param string $name
     */
    public function __construct(string $column, string $name = 'To')
    {
        $this->column = $column;
        $this->name   = $name;
    }

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
        return $query->whereDate($this->column, '<=', Carbon::parse($value));
    }
}
