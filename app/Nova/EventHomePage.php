<?php

namespace App\Nova;

use App\Enums\EventPositionType;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use NovaAttachMany\AttachMany;
use OwenMelbz\RadioField\RadioButton;
use R64\NovaFields\JSON;

class EventHomePage extends Resource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\FeaturedSpace';
    /*
     * Appear in settings Menu
     */
    public static $group = 'Settings';

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
            
            JSON::make('Position', [
                RadioButton::make('', 'position')
                       ->options([
                           EventPositionType::TOP()->getValue()   => ucfirst(EventPositionType::TOP()),
                           EventPositionType::LEFT()->getValue()  => ucfirst(EventPositionType::LEFT()),
                           EventPositionType::RIGHT()->getValue() => ucfirst(EventPositionType::RIGHT()),
                       ])
                       ->default(EventPositionType::TOP())// optional
                       ->stack(),
            ], 'data')
            ->onlyOnForms(),

            AttachMany::make('Space', 'spaces')
                      //Overwrite spaces value
                      ->fillUsing(function ($request, $model) {
                          return ;
                      })
                      ->height('300px')
                      ->rules('min:1', 'max:1'),

            Text::make('Space', function () {
                $space = $this->space;
                if ($space) {
                    return $space->name;
                }
                else {
                    return '-';
                }
            })
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            
            Textarea::make('Write Up', 'description')
                    ->alwaysShow()
                    ->rules('required')
                    ->showOnIndex(),
            
            Text::make('Position', function () {
                $data = $this->data;
                return ucfirst($data['position']);
            })
                ->exceptOnForms(),


            Images::make('Background Photo', 'photo')// second parameter is the media collection name
                  ->croppable(false)
                  ->singleImageRules('max:5000')
                  ->hideFromIndex()
                  ->rules('required'),
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
