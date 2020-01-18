<?php

namespace App\Nova;

use Fourstacks\NovaRepeatableFields\Repeater;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class BoothAssignment extends Resource
{
    /**
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\BoothAssignment';

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
    public static $search = [];

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
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make('ID')->exceptOnForms(),

            Text::make('Space code')->hideFromIndex()->readonly(),

            Text::make('Unit code')->hideFromIndex()->readonly(),

            Text::make('Period code')->hideFromIndex()->readonly(),

            Date::make('From')->format('d/M/Y')->readonly(),

            Date::make('To')->format('d/M/Y')->readonly(),

            Number::make('Quantity')->exceptOnForms(),

            Repeater::make('Allocated booths')
                ->addField([
                    'label' => 'Booth no.',
                    'name'  => 'booth_number',
                    'type'  => 'number',
                ])
                ->maximumRows($this->quantity)
                ->initialRows($this->quantity),

            Boolean::make('Notify customer')->onlyOnForms(),

            Text::make('Allocated booths', function () {
                $text = '';

                foreach($this->allocatedBooths as $allocated) {
                    $text .= $this->resource->getBoothCode($allocated['booth_number']) . "<br>";
                }

                return $text;
            })
                ->asHtml()
                ->onlyOnIndex(),

            Text::make('Ad-hoc and add-ons', function () {
                if (!$this->resource->booking->data) return '';

                $adhocs = $this->resource->booking->data['calculations']['adhocItems']['calculations'];
                $addOns = $this->resource->booking->data['calculations']['addOns']['calculations'];
                $text = '<div class="text-sm">';

                foreach ($adhocs as $adhoc) {
                    $text .= "{$adhoc['item']['name']} x {$adhoc['item']['quantity']}<br>";
                }

                foreach ($addOns as $addOn) {
                    $text .= "{$addOn['name']} x {$addOn['quantity']}<br>";
                }

                $text .= '</div>';

                return $text;
            })
                ->asHtml()
                ->onlyOnIndex(),
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
}
