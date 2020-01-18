<?php

namespace App\Nova;

use App\Enums\DiscountRateType;
use App\Enums\DiscountStatus;
use App\Enums\DiscountType;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use R64\NovaFields\JSON;
use App\Helpers\Money;

class Discount extends Resource
{

    use HasDependencies;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Discount';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
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

            Select::make('Type', 'type')
                  ->options([
                      DiscountType::QUANTITY()->getValue()     => ucfirst(DiscountType::QUANTITY()),
                      DiscountType::PERIOD()->getValue()       => ucfirst(DiscountType::PERIOD()),
                      DiscountType::LIMITED_TIME()->getValue() => ucfirst(DiscountType::LIMITED_TIME()),
                  ])
                  ->rules('required')
                  ->displayUsingLabels(),

            Text::make('Name')
                ->rules('required', 'max:100'),
            /*
             * Dependency Container For Quantity Discount
             */
            NovaDependencyContainer::make([
                JSON::make('', [
                    Number::make('No Of Units', 'no_of_units')
                          ->rules('required', 'integer', 'min:1'),

                    Text::make('Rate')
                        ->rules('required')
                        //Convert rate to cents
                        ->fillUsing(function ($request, $model) {
                            $model['data->rate'] = Money::toCents($request->data['rate']);
                        })
                        //Convert rate from cents to dollars
                        ->displayUsing(function () {
                            return Money::toDollars($this->data['rate']);
                        }),

                    Select::make('Rate Type')
                          ->options([
                              DiscountRateType::FIXED()->getValue()      => ucfirst(DiscountRateType::FIXED()),
                              DiscountRateType::PERCENTAGE()->getValue() => ucfirst(DiscountRateType::PERCENTAGE()),
                          ])
                          ->displayUsingLabels()
                          ->rules('required'),
                ], 'data'),
            ])
                                   ->dependsOn('type', DiscountType::QUANTITY()),

            /*
             * Dependency Container For Period Discount
             */
            NovaDependencyContainer::make([
                JSON::make('', [
                    Number::make('No Of Periods', 'no_of_units')
                          ->rules('required', 'integer', 'min:1'),

                    Text::make('Rate')
                        ->rules('required')
                        //Convert rate to cents
                        ->fillUsing(function ($request, $model) {
                            $model['data->rate'] = Money::toCents($request->data['rate']);
                        })
                        //Convert rate from cents to dollars
                        ->displayUsing(function () {
                            return Money::toDollars($this->data['rate']);
                        }),

                    Select::make('Rate Type')
                          ->options([
                              DiscountRateType::FIXED()->getValue()      => ucfirst(DiscountRateType::FIXED()),
                              DiscountRateType::PERCENTAGE()->getValue() => ucfirst(DiscountRateType::PERCENTAGE()),
                          ])
                          ->displayUsingLabels()
                          ->rules('required'),
                ], 'data'),
            ])
                                   ->dependsOn('type', DiscountType::PERIOD()),
            /*
             * Dependency Container For Limited Time Discount
             */
            NovaDependencyContainer::make([
                JSON::make('', [
                    Text::make('Rate')
                        ->rules('required')
                        //Convert rate to cents
                        ->fillUsing(function ($request, $model) {
                            $model['data->rate'] = Money::toCents($request->data['rate']);
                        })
                        //Convert rate from cents to dollars
                        ->displayUsing(function () {
                            return Money::toDollars($this->data['rate']);
                        }),

                    Select::make('Rate Type')
                          ->options([
                              DiscountRateType::FIXED()->getValue()      => ucfirst(DiscountRateType::FIXED()),
                              DiscountRateType::PERCENTAGE()->getValue() => ucfirst(DiscountRateType::PERCENTAGE()),
                          ])
                          ->displayUsingLabels()
                          ->rules('required'),

                    Date::make('Start Date')
                        ->withMeta(['value' => $this->start_date ?? today()]),
                    
                    Date::make('End Date')
                        ->withMeta(['value' => $this->end_date ?? NULL]),
                ], 'data'),
            ])
                                   ->dependsOn('type', DiscountType::LIMITED_TIME()),

            Text::make('Rate', function () {
                $data = $this->data;
                if ($data['rate_type'] == DiscountRateType::PERCENTAGE()) {
                    return Money::toDollars($data['rate']) . ' ' . ucfirst($data['rate_type']);
                }
                else {
                    return ucfirst($data['rate_type']) . ' ' . Money::toDollars($data['rate']);
                }
            })
                ->onlyOnIndex(),

            Select::make('Status')
                  ->options([
                      DiscountStatus::INACTIVE()->getValue() => ucfirst(DiscountStatus::INACTIVE()),
                      DiscountStatus::ACTIVE()->getValue()   => ucfirst(DiscountStatus::ACTIVE()),
                  ])
                  ->withMeta(['value' => $this->status ?? DiscountStatus::ACTIVE()])
                  ->rules('required')
                  ->onlyOnForms(),
                    
            Text::make('Status', function () {
                return ucfirst($this->status);
            })
                ->exceptOnForms(),
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
            new Filters\DiscountFilter(),
            new Filters\DiscountTypeFilter(),
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
     * This query determines to show DiscountStatus::ACTIVE() instances of the model may be attached to other resources.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return $query->where('status', DiscountStatus::ACTIVE());
    }

}
