<?php

namespace App\Nova\Filters;

use App\Models\Space;
use DKulyk\Nova\DependentFilter;
use Illuminate\Http\Request;

class SpaceDependentFilter extends DependentFilter
{
    /**
     * Name of filter.
     *
     * @var string
     */
    public $name = 'Space';

    /**
     * Attribute name of filter. Also it is key of filter.
     *
     * @var string
     */
    public $attribute = 'space_id';

    /**
     * @param \Illuminate\Http\Request $request
     * @param array                    $filters
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function options(Request $request, array $filters = [])
    {
        return Space::pluck('name', 'id');
    }
}
