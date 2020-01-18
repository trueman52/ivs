<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use R64\NovaFields\JSON;
use Laravel\Nova\Fields\BelongsTo;
use App\Enums\BusinessAgeSize;
use App\Enums\BusinessRevenueSize;
use App\Enums\BusinessTeamSize;
use App\Enums\BusinessTicketSize;

class Business extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\BusinessDetail';

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
            
            Select::make('Age Of Business(Years)', 'age')->options([
                  BusinessAgeSize::AGE_UNDER_1_YEAR()->getValue()  => ucfirst(BusinessAgeSize::AGE_UNDER_1_YEAR()),
                  BusinessAgeSize::AGE_2_TO_5_YEARS()->getValue()  => ucfirst(BusinessAgeSize::AGE_2_TO_5_YEARS()),
                  BusinessAgeSize::AGE_ABOVE_5_YEARS()->getValue() => ucfirst(BusinessAgeSize::AGE_ABOVE_5_YEARS()),
            ])
                  ->rules('required')
                  ->displayUsingLabels(),

            Select::make('Revenue Size', 'revenue')->options([
                  BusinessRevenueSize::REVENUE_SIZE_UNDER_100K()->getValue() => ucfirst(BusinessRevenueSize::REVENUE_SIZE_UNDER_100K()),
                  BusinessRevenueSize::REVENUE_SIZE_100K_TO_1M()->getValue() => ucfirst(BusinessRevenueSize::REVENUE_SIZE_100K_TO_1M()),
                  BusinessRevenueSize::REVENUE_SIZE_1M_TO_5M()->getValue()   => ucfirst(BusinessRevenueSize::REVENUE_SIZE_1M_TO_5M()),
                  BusinessRevenueSize::REVENUE_SIZE_ABOVE_5M()->getValue()   => ucfirst(BusinessRevenueSize::REVENUE_SIZE_ABOVE_5M()),
            ])
                  ->rules('required')
                  ->displayUsingLabels(),

            Select::make('Team Size', 'team_size')->options([
                  BusinessTeamSize::TEAM_SIZE_UNDER_10()->getValue() => ucfirst(BusinessTeamSize::TEAM_SIZE_UNDER_10()),
                  BusinessTeamSize::TEAM_SIZE_11_TO_50()->getValue() => ucfirst(BusinessTeamSize::TEAM_SIZE_11_TO_50()),
                  BusinessTeamSize::TEAM_SIZE_ABOVE_50()->getValue() => ucfirst(BusinessTeamSize::TEAM_SIZE_ABOVE_50()),
            ])
                  ->rules('required')
                  ->displayUsingLabels(),

            Select::make('Average Ticket Size Item(SGD)', 'average_ticket_size')->options([
                  BusinessTicketSize::TICKET_SIZE_UNDER_10()->getValue()  => ucfirst(BusinessTicketSize::TICKET_SIZE_UNDER_10()),
                  BusinessTicketSize::TICKET_SIZE_10_TO_50()->getValue()  => ucfirst(BusinessTicketSize::TICKET_SIZE_10_TO_50()),
                  BusinessTicketSize::TICKET_SIZE_51_TO_100()->getValue() => ucfirst(BusinessTicketSize::TICKET_SIZE_51_TO_100()),
                  BusinessTicketSize::TICKET_SIZE_ABOVE_100()->getValue() => ucfirst(BusinessTicketSize::TICKET_SIZE_ABOVE_100()),
            ])
                  ->rules('required')
                  ->displayUsingLabels(),

            JSON::make('URLS', [
                Text::make('Website'),

                Text::make('Facebook'),

                Text::make('Instagram'),
            ]),
            
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
