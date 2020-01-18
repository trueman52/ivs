<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\AddOnGroup;
use App\Http\Requests\AddOnRequest;
use App\Helpers\Money;

class AddOn extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\AddOn';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'backend_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'backend_name', 'frontend_name',
    ];


    /**
     * Get the fields displayed by the User resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()
              ->hideFromIndex(),

            Text::make('Add-on Name', 'backend_name')
                ->sortable()
                ->rules('required', 'max:100'),

            Text::make('Label For Customer', 'frontend_name')
                ->sortable()
                ->rules('required', 'max:100'),

            Textarea::make('Description')
                    ->hideFromIndex(),

            Images::make('Photo')
                  ->croppable(false)
                  ->rules('required'),

            BelongsToMany::make('Add On Groups', 'addOnGroups')
                         ->fields(function ($request) {
                             return [
                                 Number::make('Min Qty', 'min')
                                       ->rules('required', 'integer', 'min:1')
                                       ->displayUsing(function () {
                                           return isset($this->pivot) ? $this->pivot->min : 'N/A';
                                       }),
                                               
                                 Number::make('Max Qty', 'max')
                                       ->rules('required', 'integer', 'min:1', AddOnRequest::maxValidatorRule($request, 'max'))
                                       ->displayUsing(function () {
                                           return isset($this->pivot) ? $this->pivot->max : 'N/A';
                                       }),

                               Text::make('Cost Per Unit')
                                   ->rules('required', 'numeric', 'min:1')
                                   //Convert cost_per_unit to cents
                                   ->fillUsing(function ($request, $model) {
                                        $model['cost_per_unit'] = Money::toCents($request->cost_per_unit);
                                    })
                                    //Convert cost_per_unit from cents to dollars
                                    ->displayUsing(function () {
                                        return isset($this->pivot) ? Money::toDollars($this->pivot->cost_per_unit) : 'N/A';
                                    }),
                             ];
                         })
                         ->hideFromDetail(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
    
    /**
     * This query determines to show only available instances of the model may be attached to other resources.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param                                         $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {   
        $addOnGroup = AddOnGroup::find($request->resourceId);
        $current = $request->current;
        if(!$current) {
            $current = $request->addOns;
        }
        if($current) {
            return $query->whereDoesntHave('addOnGroups', function ($query) use($addOnGroup, $current) {
                $query->where('add_on_group_id', $addOnGroup->id)->where('add_on_id', '<>', $current);
            });
        } else {
            return $query->whereDoesntHave('addOnGroups', function ($query) use($addOnGroup) {
                $query->where('add_on_group_id', $addOnGroup->id);
            });
        }
    }
}
