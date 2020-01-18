<?php

namespace App\Nova;

use App\Enums\SpaceStatus;
use App\Enums\TagType;
use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use App\Nova\Lenses\SpaceTakeUp;
use Carbon\Carbon;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Eminiarts\Tabs\Tabs;
use Fourstacks\NovaRepeatableFields\Repeater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Jackabox\DuplicateField\DuplicateField;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use NovaAttachMany\AttachMany;
use R64\NovaFields\JSON;
use Yassi\NestedForm\NestedForm;
use App\Rules\MaxWordsRule;
use App\Rules\ValidSpacePublishRule;

class Space extends Resource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Space';

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

    /*
     * Eager Load
     */
    public static $with = ['tags'];

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
     * Get the fields displayed by the User resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Heading::make('Required')
                   ->onlyOnForms(),
            
            ID::make()
              ->hideFromIndex(),

            Text::make('Title', 'name', function () {
                    return "<a href='/spaces/{$this->id}'>{$this->name}</a>";
                })
                ->asHtml()
                ->sortable()
                ->onlyOnIndex(),
            
            Text::make('Title', 'name')
                ->rules('required', 'max:255', new MaxWordsRule(2))
                ->hideFromIndex(),
            
            Text::make('Tags', function () {
                $categories            = $this->tags()->get();
                if (count($categories)) {
                    $displayCategories = '';
                    foreach ($categories as $key => $category) {
                        if ($key != 0) $displayCategories .= ', ';
                        $displayCategories .= $category->name;
                    }
                    return $displayCategories;
                }
                else {
                    return 'N/A';
                }
            })
                ->onlyOnIndex(),

            Text::make('Location', function () {
                return $this->street . ' ' . $this->postal_code . ' ' . $this->country;
            })
                ->onlyOnIndex(),

            Text::make('Space Code', 'code')
                ->sortable()
                ->rules('required', 'max:10')
                ->creationRules('unique:spaces,code')
                ->updateRules('unique:spaces,code,{{resourceId}}')
                ->hideFromIndex()
                ->help('Max. 10 characters. Must be unique for every Space'),
                    
            Text::make('Tag Line', 'remarks')
                ->rules('required', 'max:255')
                ->hideFromIndex(),
                    
            Text::make('Date To Show On Listing', 'display_date')
                ->rules('required', 'max:255')
                ->hideFromIndex(),
                    
            Boolean::make('Curation Required?', 'needs_curation')
                   ->rules('required')
                   ->hideFromIndex()
                   ->hideFromIndex(),

            Textarea::make('Write Up', 'description')
                    ->rules('required')
                    ->hideFromIndex(),

            Place::make('Location', 'street')
                 ->onlyOnDetail(),

            Text::make('', 'postal_code')
                ->onlyOnDetail(),

            Country::make('', 'country')
                   ->onlyOnDetail(),

            Date::make('Booking Closing Date', 'booking_closing_at')
                ->rules('required', 'after:yesterday')
                ->hideFromIndex()
                ->help('at 23.59 hrs'),
            
            Repeater::make('Highlights')
                    ->addField([
                        'label'       => 'Highlights',
                        'name'        => 'text',
                        'type'        => 'text'
                    ])
                    ->initialRows(1)
                    ->addButtonText('Add New')
                    ->hideFromIndex(),

            /*
             * Using Pivot Table for Notable Features
             */
            AttachMany::make('Notable Features', 'features')
                      ->showCounts()
                      ->fillUsing(function ($request, $model) {
                          return function () use ($model, $request) {
                              $features    = json_decode($request->features, true);
                              $syncFeature = [];
                              foreach ($features as $feature) {
                                  $syncFeature[$feature] = ['tag_id' => $feature, 'tag_type' => TagType::NOTABLE_FEATURE()];
                              }
                              $model->features()->sync($syncFeature);
                          };
                      })
                      ->hideToolbar()
                      ->height('150px')
                      ->showPreview()
                      ->rules('min:1'),

            /*
             * Show Notable Features Table Data In Detail View
             */
            Text::make('Notable Features', 'features', function () {
                $features = $this->features()->get();
                if (count($features)) {
                    $displayFeatures = '';
                    foreach ($features as $key => $feature) {
                        if ($key != 0) $displayFeatures .= ', ';
                        $displayFeatures .= $feature->name;
                    }
                    return $displayFeatures;
                }
                else {
                    return 'N/A';
                }
            })
                ->onlyOnDetail(),

            BelongsTo::make('Space In Charge', 'inCharge', 'App\Nova\User')
                     ->rules('required'),
                    
            Select::make('Status')
                  ->options([
                      SpaceStatus::DRAFT()->getValue()       => ucfirst(SpaceStatus::DRAFT()),
                      SpaceStatus::UNPUBLISHED()->getValue() => ucfirst(SpaceStatus::UNPUBLISHED()),
                  ])
                  ->withMeta(['value' => $this->status ?? SpaceStatus::DRAFT()])
                  ->rules('required')
                  ->onlyOnForms()
                  ->hideWhenUpdating(),
                    
            Select::make('Status')
                  ->options([
                      SpaceStatus::DRAFT()->getValue()       => ucfirst(SpaceStatus::DRAFT()),
                      SpaceStatus::PUBLISHED()->getValue()   => ucfirst(SpaceStatus::PUBLISHED()),
                      SpaceStatus::UNPUBLISHED()->getValue() => ucfirst(SpaceStatus::UNPUBLISHED()),
                  ])
                  ->withMeta(['value' => $this->status ?? SpaceStatus::DRAFT()])
                  ->rules('required')
                  ->onlyOnForms()
                  ->hideWhenCreating()
                  ->updateRules(new ValidSpacePublishRule($request->resourceId)),
                    
            Text::make('Status', function () {
                return ucfirst($this->status);
            })
                ->exceptOnForms(),

            Images::make('Banner Images', 'banners')// second parameter is the media collection name
                  ->croppable(false)
                  ->singleImageRules('max:5000')
                  ->hideFromIndex()
                  ->rules('required'),
                    
            Heading::make('Optional')
                   ->onlyOnForms(),

            JSON::make('URLS', [
                Text::make('Space URL'),

                Text::make('Facebook URL'),

                Text::make('Instagram URL'),
            ])
               ->onlyOnForms(),
                    
            Text::make('URLS', function () {
                $displayUrls = '';
                $displayUrls .= "<a href='{$this->spaceUrl}'>{$this->spaceUrl}</a>" . '<br>';
                $displayUrls .= "<a href='{$this->facebookUrl}'>{$this->facebookUrl}</a>" . '<br>';
                $displayUrls .= "<a href='{$this->instagramUrl}'>{$this->instagramUrl}</a>" . '<br>';
                return $displayUrls;
            })
                ->asHtml()
                ->onlyOnDetail(),

            /*
             * Using Pivot Table for Tags
             */
            AttachMany::make('Tags', 'tags')
                      ->showCounts()
                      ->fillUsing(function ($request, $model) {
                          return function () use ($model, $request) {
                              $syncTag = [];
                              //Check tag is checked or not
                              if($request->tags) {
                                $tags    = json_decode($request->tags, true);
                                foreach ($tags as $tag) {
                                  $syncTag[$tag] = ['tag_id' => $tag, 'tag_type' => TagType::TAG()];
                                }
                              }
                              $model->tags()->wherePivot('tag_type', TagType::TAG())->sync($syncTag);
                          };
                      })
                      ->hideToolbar()
                      ->height('150px')
                      ->showPreview(),
                              
            /*
             * Using Pivot Table for Tags
             */
            Text::make('Tags', 'tags', function () {
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
                ->onlyOnDetail(),

            Textarea::make('Announcement')
                    ->hideFromIndex(),
                              
            Files::make('Vendor Kit', 'vendor_kit')                  
                 ->hideFromIndex(),

            DuplicateField::make('Duplicate')
                          ->withMeta([
                              'resource' => 'spaces', // resource url
                              'model'    => 'App\Models\Space', // model path
                              'id'       => $this->id, // id of record
                          ]),

            NestedForm::make('Location', 'address')
                      ->heading('Location'),

            /*
             * Tab View In Space Details
             */

            new Tabs('Relations', [
                // Period Tab
                HasMany::make('Periods'),

                HasMany::make('Units'),

                BelongsToMany::make('Coordinators', 'coordinators'),
            ]),
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
            new Filters\SpaceTitleFilter(),
            new Filters\SpaceTagFilter(),
            new Filters\SpaceFilter(),
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
        return [
            new SpaceTakeUp()
        ];
    }

}
