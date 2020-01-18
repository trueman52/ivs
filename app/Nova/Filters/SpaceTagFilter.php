<?php

namespace App\Nova\Filters;

use App\Enums\TagType;
use App\Models\Tag;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class SpaceTagFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $name = 'Tags';

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
        $query->whereHas('tags', function ($query) use ($value) {
            $query->where('tag_id', $value);
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
        return Tag::where('type', TagType::TAG())->orderBy('name')->pluck('id', 'name')->all();
    }
}
