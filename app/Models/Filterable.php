<?php

namespace App\Models;

use App\Filters\Filter;

trait Filterable
{
    /**
     * Apply filters to query scope.
     *
     * @param                     $query
     * @param \App\Filters\Filter $filter
     */
    public function scopeFilter($query, Filter $filter)
    {
        $filter->apply($query);
    }
}