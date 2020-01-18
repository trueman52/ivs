<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Unit;
use App\Rules\ValidPeriodDateRule;
use App\Rules\ValidPeriodCodeRule;
use App\Helpers\Money;

class Period extends Resource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Period';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'period_detail';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [

    ];

    /**
     * @var bool
     */
    public static $displayInNavigation = false;

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

            Date::make('Start Date', 'from_date')
                ->rules('required')
		->creationRules(new ValidPeriodDateRule($request->viaResourceId, $request->to_date, 0))
		->updateRules(new ValidPeriodDateRule($request->viaResourceId, $request->to_date, $request->resourceId)),

            Date::make('End Date', 'to_date')
                ->rules('required', 'after:from_date'),

            Text::make('Code')
                ->rules('required', 'max:5')
		->creationRules(new ValidPeriodCodeRule($request->viaResourceId, 0))
		->updateRules(new ValidPeriodCodeRule($request->viaResourceId, $request->resourceId)),

            BelongsToMany::make('Units')
                         ->fields(function () {
                             return [
                                 Text::make('Unit Price')
                                     ->rules('required')
                                     //Convert unit_price to cents
                                     ->fillUsing(function ($request, $model) {
                                          $model['unit_price'] = Money::toCents($request->unit_price);
                                     })
                                     //Convert unit_price from cents to dollars
                                     ->displayUsing(function () {
                                           return isset($this->pivot) ? Money::toDollars($this->pivot->unit_price) : 'N/A';
                                     }),

                                 Number::make('Total Qty', 'max_quantity')
                                       ->rules('required', 'integer', 'min:1'),
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
     * Options for repeater field.
     *
     * @param string $placeholder
     * @param string $type
     * @param string $width
     *
     * @return array
     */
    public function repeaterFieldOptions(string $placeholder, string $type = 'text', string $width = 'w-1/4')
    {
        return [
            'label' => $placeholder,
            'type'  => $type,
            'width' => $width,
        ];
    }
    
    /**
     * @return bool
     */
    public static function searchable()
    {
        return false;
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        $unit = Unit::find($request->resourceId);
        $current = $request->current;
        if(!$current) {
            $current = $request->periods;
        }
        if($current) {
            return $query->where('space_id', $unit->space_id)->whereDoesntHave('units', function($query) use($current, $unit) {
                $query->where('period_id', '<>', $current)
                      ->where('unit_id', $unit->id);
            });
        } else {
            return $query->where('space_id', $unit->space_id)->whereDoesntHave('units', function($query) use($unit) {
                $query->where('unit_id', $unit->id);
            });
        }
    }
}
