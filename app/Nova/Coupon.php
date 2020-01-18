<?php

namespace App\Nova;

use App\Enums\CouponStatus;
use App\Enums\DiscountRateType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use R64\NovaFields\JSON;
use App\Rules\MaxNumbersRule;
use App\Helpers\Money;
use Carbon\Carbon;

class Coupon extends Resource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Coupon';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'code';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'code', 'data->description',
    ];

    /*
     * Eager Load
     */
    public static $with = ['space', 'customer', 'space.periods', 'creator'];

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

            Text::make('Code')
                ->rules('required', 'max: 10')
                ->creationRules('unique:coupons,code')
                ->updateRules('unique:coupons,code,{{resourceId}}')
                ->help('Max. 10 characters. Must be unique for every Coupon'),

            Textarea::make('Description', function () {
                return $this->data['description'];
            })
                    ->alwaysShow()
                    ->onlyOnIndex(),

            JSON::make('', [
                Text::make('Title')
                    ->rules('required'),

                Textarea::make('Description')
                        ->rules('required'),

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

                Number::make('Quota Per User')
                      ->rules('nullable', 'integer', 'min:1', new MaxNumbersRule($request->data['total_quota'])),

                Number::make('Total Quota')
                      ->rules('nullable', 'integer', 'min:1'),
            ], 'data'),

            Date::make('Usable Between', 'valid_from')
                ->withMeta($this->valid_from ? [] : ["value" => Carbon::now()->format('Y-m-d')]) 
                ->rules('nullable')
                ->hideFromIndex(),

            Date::make('', 'valid_to')
                ->rules('nullable', 'after:valid_from')
                ->hideFromIndex(),

            BelongsTo::make('Customer')
                     ->nullable()
                     ->searchable()
                     ->hideFromIndex()
                     ->help('Type in 3 or more characters to perform search.'),

            Text::make('Available Period', function () {
                if ($this->space) {
                    $periods        = $this->space->periods()->get();
                    $displayPeriods = '';
                    if (count($periods)) {
                        foreach ($periods as $key => $period) {
                            if ($key != 0) $displayPeriods .= '<br>';
                            $displayPeriods .= $period->from_date->format('Y-m-d');
                        }
                        return $displayPeriods;
                    }
                    else {
                        return 'N/A';
                    }
                }
                else {
                    return 'N/A';
                }
            })
                ->asHtml()
                ->onlyOnIndex(),

            BelongsTo::make('Space')
                     ->nullable()
                     ->searchable()
                     ->help('Type in 3 or more characters to perform search.'),

            Text::make('Quota Per User', function () {
                return $this->data['quota_per_user'];
            })
                ->onlyOnIndex(),

            Text::make('Total Quota', function () {
                return $this->data['total_quota'];
            })
                ->onlyOnIndex(),

            BelongsTo::make('User', 'creator')
                     ->onlyOnIndex(),

            Select::make('Status')
                  ->options([
                      CouponStatus::INACTIVE()->getValue() => ucfirst(CouponStatus::INACTIVE()),
                      CouponStatus::ACTIVE()->getValue()   => ucfirst(CouponStatus::ACTIVE()),
                  ])
                  ->withMeta(['value' => $this->status ?? CouponStatus::ACTIVE()])
                  ->rules('required')
                  ->onlyOnForms(),
                    
            Text::make('Status', function () {
                return ucfirst($this->status);
            })
                ->exceptOnForms(),

            Text::make('', 'created_by')
                ->withMeta(['type' => 'hidden', 'value' => Auth::user()->id])
                ->onlyOnForms(),
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
            new Filters\CouponFilter(),
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
     * This query determines to show CouponStatus::ACTIVE() instances of the model may be attached to other resources.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return $query->where('status', CouponStatus::ACTIVE());
    }

}
