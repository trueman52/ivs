<?php

namespace App\Nova;

use App\Nova\Actions\DownloadBookingReport;
use App\Nova\Actions\GenerateBoothAssignments;
use App\Nova\Actions\RegisterPayment;
use App\Nova\Filters\Booking\BookingStatusFilter;
use App\Nova\Filters\Booking\SpaceRelationshipFilter;
use App\Nova\Filters\Booking\UnitRelationshipFilter;
use App\Nova\Filters\FromDateFilter;
use App\Nova\Filters\ToDateFilter;
use App\Nova\Filters\UserRelationshipFilter;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Ivs\BookingPriceBreakdownResourceTool\BookingPriceBreakdownResourceTool;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Text;

class Booking extends Resource
{
    public static $with = [
        'unit',
        'space.address',
        'space.media',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Booking';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new DownloadBookingReport())
                ->only(['id', 'user_id', 'space_id', 'unit_id', 'created_at'])
                ->onlyOnIndex(),
            (new RegisterPayment())->onlyOnDetail(),
            (new GenerateBoothAssignments())->onlyOnDetail(),
        ];
    }

    /**
     * @return \Eminiarts\Tabs\Tabs
     */
    protected function bookingSummaryTabs()
    {
        return (new Tabs("{$this->id} - Booking summary", [
            'Booking Summary' => [
                BelongsTo::make('Space'),
                BelongsTo::make('Unit'),
                Text::make('Total payable', 'grand_total_in_dollars'),
                Text::make('Amount paid', 'paid_in_dollars'),
                Text::make('Outstanding')->exceptOnForms(),
                Text::make('Security deposit', 'deposit_in_dollars'),
                Text::make('Status'),
                Text::make('Remarks'),
                Text::make('Internal notes'),
            ],
            'Refunds'         => [
                HasMany::make('Refunds'),
            ],
        ]))->withToolBar();
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
     * @return \Eminiarts\Tabs\Tabs
     */
    protected function customerSummaryTabs()
    {
        return (new Tabs("Customer summary", [
            'Customer details' => [
                HasOne::make('Customer details', 'customerDetail'),
            ],
            'Billing details'  => [
                MorphOne::make('Billing details', 'billing', 'App\Nova\Billing'),
            ],
        ]));
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            $this->bookingSummaryTabs(),
            $this->customerSummaryTabs(),
            HasMany::make('Booth assignments', 'BoothAssignments'),
            new BookingPriceBreakdownResourceTool(),
        ];
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
            new SpaceRelationshipFilter(),
            new UnitRelationshipFilter(),
            new BookingStatusFilter(),
            new UserRelationshipFilter(),
            new FromDateFilter('created_at'),
            new ToDateFilter('created_at'),
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
}
