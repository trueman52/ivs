<?php

namespace App\Nova;

use App\Enums\TagType;
use App\Enums\UnitStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use NovaAttachMany\AttachMany;
use Jackabox\DuplicateField\DuplicateField;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use App\Rules\ValidUnitPublishRule;
use App\Helpers\Money;

class Unit extends Resource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Unit';

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

    ];

    /**
     * @var bool
     */
    public static $displayInNavigation = false;

    /*
     * Eager Load
     */
    public static $with = ['tags', 'periods'];

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

            Text::make('Unit Name', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Unit Code', 'code')
                ->sortable()
                ->rules('required', 'max:3')
                ->creationRules('unique:units,code')
                ->updateRules('unique:units,code,{{resourceId}}')
                ->help('Max. 3 characters. Must be unique for every Unit'),

            Textarea::make("Whats's Included", 'description')
                    ->rules('required')
                    ->hideFromIndex(),

            Text::make('Security Deposit Required', 'security_deposit')
                  ->rules('required')
                  //Convert security_deposit to cents
                  ->fillUsing(function ($request, $model) {
                      $model['security_deposit'] = Money::toCents($request->security_deposit);
                  })
                  //Convert security_deposit from cents to dollars
                  ->displayUsing(function () {
                      return Money::toDollars($this->security_deposit);
                  })
                  ->hideFromIndex(),

            Text::make('SD?', function () {
                if ($this->security_deposit > 0) {
                    return 'Yes';
                }
                else {
                    return 'No';
                }
            })
                ->onlyOnIndex(),

            /*
             * Using Pivot Table for Things To Note
             */
            AttachMany::make('Things To Note', 'things')
                      ->showCounts()
                      //Save Things To Note value
                      ->fillUsing(function ($request, $model) {
                          return function () use ($model, $request) {
                              $things     = json_decode($request->things, true);
                              $syncThings = [];
                              foreach ($things as $thing) {
                                $syncThings[$thing] = ['tag_id' => $thing, 'tag_type' => TagType::THINGS_TO_NOTE()];
                              }
                              $model->things()->wherePivot('tag_type', TagType::THINGS_TO_NOTE())->sync($syncThings);
                          };
                      })
                      ->hideToolbar()
                      ->height('300px')
                      ->showPreview()
                      ->rules('min:1'),
            
            /*
             * Show Things To Note Table Data
             */
            Text::make('Things To Note', function () {
                $things = $this->things()->get();
                if (count($things)) {
                    $displayThings = '';
                    foreach ($things as $key => $thing) {
                        if ($key != 0) $displayThings .= ', ';
                        $displayThings .= $thing->name;
                    }
                    return $displayThings;
                }
                else {
                    return 'N/A';
                }
            })
                ->onlyOnDetail(),

            Textarea::make('Specific Pointers', 'additional_information')
                    ->rules('required')
                    ->hideFromIndex(),

            Select::make('Status')
                  ->options([
                      UnitStatus::UNPUBLISHED()->getValue() => ucfirst(UnitStatus::UNPUBLISHED()),
                      UnitStatus::SOLD_OUT()->getValue()    => ucfirst(UnitStatus::SOLD_OUT()),
                  ])
                  ->withMeta(['value' => $this->status ?? UnitStatus::UNPUBLISHED()])
                  ->rules('required')
                  ->onlyOnForms()
                  ->hideWhenUpdating(),
                    
            Select::make('Status')
                  ->options([
                      UnitStatus::PUBLISHED()->getValue()   => ucfirst(UnitStatus::PUBLISHED()),
                      UnitStatus::UNPUBLISHED()->getValue() => ucfirst(UnitStatus::UNPUBLISHED()),
                      UnitStatus::SOLD_OUT()->getValue()    => ucfirst(UnitStatus::SOLD_OUT()),
                  ])
                  ->withMeta(['value' => $this->status ?? UnitStatus::UNPUBLISHED()])
                  ->rules('required')
                  ->onlyOnForms()
                  ->hideWhenCreating()
                  ->updateRules(new ValidUnitPublishRule($request->resourceId)),
                    
            Text::make('Status', function () {
                return ucfirst($this->status);
            })
                ->exceptOnForms(),

            /*
             * Using Pivot Table for Addon Groups
             */
            AttachMany::make('Add On Groups', 'addOnGroups')
                      ->showCounts()
                      //Save Addon Groups value
                      ->fillUsing(function ($request, $model) {
                          return function () use ($model, $request) {
                              $addOnGroups     = json_decode($request->addOnGroups, true);
                              $syncAddOnGroups = [];
                              foreach ($addOnGroups as $addOnGroup) {
                                  $syncAddOnGroups[$addOnGroup] = ['add_on_group_id' => $addOnGroup];
                              }
                              $model->addOnGroups()->sync($syncAddOnGroups);
                          };
                      })
                      ->height('300px')
                      ->showPreview(),

            /*
             * Show Add On Group Table Data
             */
            Text::make('Add On Groups', function () {
                $addOnGroups = $this->addOnGroups()->get();
                if (count($addOnGroups)) {
                    $displayAddOnGroups = '';
                    foreach ($addOnGroups as $key => $addOnGroup) {
                        if ($key != 0) $displayAddOnGroups .= '<br>';
                        $displayAddOnGroups .= $addOnGroup->backend_name;
                    }
                    return $displayAddOnGroups;
                }
                else {
                    return 'N/A';
                }
            })
                ->asHtml()
                ->onlyOnDetail(),

            /*
             * Using Pivot Table for Discounts
             */
            AttachMany::make('Discounts')
                      ->showCounts()
                      //Save Discounts value
                      ->fillUsing(function ($request, $model) {
                          return function () use ($model, $request) {
                              $discounts     = json_decode($request->discounts, true);
                              $syncDiscounts = [];
                              foreach ($discounts as $discount) {
                                  $syncDiscounts[$discount] = ['discount_id' => $discount];
                              }
                              $model->discounts()->sync($syncDiscounts);
                          };
                      })
                      ->height('300px')
                      ->showPreview(),

            /*
             * Show Discount Table Data
             */
            Text::make('Discounts', function () {
                $discounts        = $this->discounts()->get();
                if (count($discounts)) {
                    $displayDiscounts = '';
                    foreach ($discounts as $key => $discount) {
                        if ($key != 0) $displayDiscounts .= '<br>';
                        $displayDiscounts .= $discount->name;
                    }
                    return $displayDiscounts;
                }
                else {
                    return 'N/A';
                }
            })
                ->asHtml()
                ->onlyOnDetail(),

            /*
             * Using Pivot Table for Tags
             */
            AttachMany::make('Tags', 'tags')
                      ->showCounts()
                      //Save Tags value
                      ->fillUsing(function ($request, $model) {
                          return function () use ($model, $request) {
                              $tags     = json_decode($request->tags, true);
                              $syncTags = [];
                              foreach ($tags as $tag) {
                                  $syncTags[$tag] = ['tag_id' => $tag, 'tag_type' => TagType::TAG()];
                              }
                              $model->tags()->wherePivot('tag_type', TagType::TAG())->sync($syncTags);
                          };
                      })
                      ->hideToolbar()
                      ->height('300px')
                      ->showPreview(),

            /*
             * Show Tag Table Data
             */
            Text::make('Tags', function () {
                $tags = $this->tags()->get();
                if (count($tags)) {
                    $displayTags = '';
                    foreach ($tags as $key => $tag) {
                        if ($key != 0) $displayTags .= ', ';
                        $displayTags .= $tag->name;
                    }
                    return $displayTags;
                }
                else {
                    return 'N/A';
                }
            })
                ->showOnIndex()
                ->showOnDetail(),
                    
            Text::make('Starting Price', function () {
                $period = $this->periods()->orderBy('period_unit.unit_price', 'ASC')->first();
                if ($period) {
                    return '$$ ' . Money::toDollars($period->pivot->unit_price);
                } else {
                    return 'N/A';
                }
            })
                ->asHtml()
                ->onlyOnIndex(),
                    
            Images::make('Image', 'photo')
                  ->croppable(false)
                  ->singleImageRules('max:5000')
                  ->hideFromIndex()
                  ->rules('required'),

            BelongsToMany::make('Periods')
                         ->fields(function () {
                             return [
                                 Text::make('Unit Price')
                                     ->rules('required', 'numeric', 'min:1')
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
                         }),
                                 
            DuplicateField::make('Duplicate')
                          ->withMeta([
                              'resource' => 'units',
                              'model'    => 'App\Models\Unit',
                              'id'       => $this->id,
                          ]),
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

}
