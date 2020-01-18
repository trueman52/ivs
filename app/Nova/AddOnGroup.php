<?php

namespace App\Nova;

use App\Enums\AddOnGroupStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Http\Requests\AddOnRequest;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Money;

class AddOnGroup extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\AddOnGroup';

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

            Text::make('Name', 'backend_name')
                ->sortable()
                ->rules('required', 'max:100'),

            Text::make('Label For Customer', 'frontend_name')
                ->sortable()
                ->rules('required', 'max:100'),

            Select::make('Status', 'status')->options([
                AddOnGroupStatus::INACTIVE()->getValue() => ucfirst(AddOnGroupStatus::INACTIVE()),
                AddOnGroupStatus::ACTIVE()->getValue()   => ucfirst(AddOnGroupStatus::ACTIVE()),
            ])
                  ->withMeta(['value' => $this->status ?? AddOnGroupStatus::ACTIVE()])
                  ->rules('required')
                  ->onlyOnForms(),
            
            Text::make('Status', function () {
                return ucfirst($this->status);
            })
                ->exceptOnForms(),

            BelongsToMany::make('Add Ons', 'addOns')
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
                         }),
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
        return [
            new Filters\AddOnGroupFilter(),
        ];
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
     * This query determines to show only AddOnGroupStatus::ACTIVE() instances of the model may be attached to other resources.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return $query->where('status', AddOnGroupStatus::ACTIVE());
    }
    
    /**
     * Overwrite validatorForAttachment query to validate if the user select the relation value.
     * Solution found in https://github.com/laravel/nova-issues/issues/969
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return bool
     */
    public static function validatorForAttachment($request)
    {
        $validator = parent::validatorForAttachment($request);
        $attribute = $request->input('viaRelationship', false);

        if (filled($attribute)) {
            $field = (new static(static::newModel()))
                ->availableFields($request)
                ->firstWhere('attribute', $attribute);

            if (filled($field) && $field->resourceName !== $field->attribute) {
                $name = $field->resourceName;
                $rules = static::creationRulesFor($request, $attribute);
                $rules[$name] = $rules[$attribute];

                $relationValidator = Validator::make($request->all(), $rules, [], [
                    $name => $field->name,
                ]);

                if (! $relationValidator->passes() && $relationValidator->errors()->has($name)) {
                    $validator->after(function ($validator) use ($name, $relationValidator, $attribute) {
                        $validator->errors()->add($attribute, $relationValidator->errors()->first($name));
                    });
                }
            }
        }

        return $validator;
    }
    
}
