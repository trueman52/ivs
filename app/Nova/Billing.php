<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use R64\NovaFields\JSON;
use Yassi\NestedForm\NestedForm;

class Billing extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\BillingDetail';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;


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
             Text::make('First Name')
                ->rules('required', 'max:255'),

            Text::make('Last Name')
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->rules('required', 'max:255', 'email'),
            
            JSON::make('Contact Number', [
                Text::make('', 'code')
                    ->rules('required', 'max:3'),

                Text::make('', 'number')
                    ->rules('required', 'max:20'),
            ], 'contact_number'),
            
            Text::make('Company Name', 'company_name')
                ->rules('nullable', 'max:255'),
            
            BelongsTo::make('Customer', 'detailable'),
            
            NestedForm::make('Address')
                      ->heading('Address'),
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


}
