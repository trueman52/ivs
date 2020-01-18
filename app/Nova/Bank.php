<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;

class Bank extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\BankDetail';

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
            ID::make()
                 ->sortable(),
            
            Text::make('Bank Name')
                ->rules('required', 'max:30'),

            Text::make('Bank Code')
                ->rules('required', 'max:15'),

            Text::make('Account Type')
                ->rules('required', 'max:30'),

            Text::make('Branch Code')
                ->rules('required', 'max:30'),

            Text::make('Account Number')
                ->rules('required', 'max:15'),

            Text::make('Name', 'holder_name')
                ->rules('required', 'max:100'),

            Textarea::make('Location')
                    ->alwaysShow(),

            Textarea::make('Account Address')
                    ->alwaysShow(),
            
            BelongsTo::make('Customer', 'user'),
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
