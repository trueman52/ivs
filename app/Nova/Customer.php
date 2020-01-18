<?php

namespace App\Nova;

use App\Enums\BusinessAgeSize;
use App\Enums\BusinessRevenueSize;
use App\Enums\BusinessTeamSize;
use App\Enums\BusinessTicketSize;
use App\Enums\CustomerStatus;
use App\Enums\TagType;
use App\Models\Role;
use App\Nova\Actions\DownloadCustomerReport;
use App\Nova\Filters\Customer\SpaceRelationshipFilter;
use App\Nova\Filters\Customer\UnitRelationshipFilter;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use NovaAttachMany\AttachMany;
use R64\NovaFields\JSON;
use Yassi\NestedForm\NestedForm;

class Customer extends Resource
{
    use TabsOnEdit; // Use this Trait

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'fullName';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'last_name', 'email',
    ];

    /*
     * Eager Load
     */
    public static $with = ['roles', 'profile', 'profile.address', 'billing'];

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
            new DownloadCustomerReport()
        ];
    }

    /**
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function addressFields($obj)
    {
        return $this->merge([

            Place::make('Address', $obj . 'street')
                ->onlyOnDetail(),

            Text::make('City', $obj . 'city')
                ->onlyOnDetail(),

            Text::make('State', $obj . 'state')
                ->onlyOnDetail(),

            Text::make('Postal Code', $obj . 'postal_code')
                ->onlyOnDetail(),

            Country::make('Country', $obj . 'country')
                ->withMeta(['value' => $this->{$obj . 'country'} ?? 'SG'])
                ->onlyOnDetail(),
        ]);
    }

    /**
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function bankAccounts()
    {
        return $this->merge([

            NestedForm::make('Bank')
                ->heading('Bank')
                ->hideWhenCreating(),

            Text::make('Bank Name')
                ->rules('required', 'max:50')
                ->onlyOnDetail(),

            Text::make('Bank Code')
                ->rules('required', 'max:15')
                ->onlyOnDetail(),

            Text::make('Account Type')
                ->rules('required', 'max:30')
                ->onlyOnDetail(),

            Text::make('Branch Code')
                ->rules('required', 'max:15')
                ->onlyOnDetail(),

            Text::make('Account Number')
                ->rules('required', 'max:30')
                ->onlyOnDetail(),

            Text::make('Name', 'holder_name')
                ->rules('required', 'max:100')
                ->onlyOnDetail(),

            Textarea::make('Location')
                ->alwaysShow()
                ->onlyOnDetail(),

            Textarea::make('Account Address')
                ->alwaysShow()
                ->onlyOnDetail(),
        ]);
    }

    /**
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function billingInformations()
    {
        return $this->merge([

            NestedForm::make('Billing')
                ->heading('Billing')
                ->hideWhenCreating(),

            Text::make('First Name', 'billing_first_name')
                ->rules('required', 'max:255')
                ->onlyOnDetail(),

            Text::make('Last Name', 'billing_last_name')
                ->rules('required', 'max:255')
                ->onlyOnDetail(),

            Text::make('Email', 'billing_email')
                ->rules('required', 'max:255', 'email')
                ->onlyOnDetail(),

            Text::make('Contact Number', function () {
                return $this->billing_country_code . ' ' . $this->billing_contact_number;
            })
                ->onlyOnDetail(),

            $this->addressFields('billing_'),

            Text::make('Company Name', 'billing_company_name')
                ->rules('nullable', 'max:255')
                ->onlyOnDetail(),
        ]);
    }

    /**
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function businessInformations()
    {
        return $this->merge([

            NestedForm::make('Business')
                ->heading('Business')
                ->hideWhenCreating(),

            Select::make('Age Of Business(Years)', 'age')->options([
                BusinessAgeSize::AGE_UNDER_1_YEAR()->getValue()  => ucfirst(BusinessAgeSize::AGE_UNDER_1_YEAR()),
                BusinessAgeSize::AGE_2_TO_5_YEARS()->getValue()  => ucfirst(BusinessAgeSize::AGE_2_TO_5_YEARS()),
                BusinessAgeSize::AGE_ABOVE_5_YEARS()->getValue() => ucfirst(BusinessAgeSize::AGE_ABOVE_5_YEARS()),
            ])
                ->rules('required')
                ->displayUsingLabels()
                ->onlyOnDetail(),

            Select::make('Revenue Size', 'revenue')->options([
                BusinessRevenueSize::REVENUE_SIZE_UNDER_100K()->getValue() => ucfirst(BusinessRevenueSize::REVENUE_SIZE_UNDER_100K()),
                BusinessRevenueSize::REVENUE_SIZE_100K_TO_1M()->getValue() => ucfirst(BusinessRevenueSize::REVENUE_SIZE_100K_TO_1M()),
                BusinessRevenueSize::REVENUE_SIZE_1M_TO_5M()->getValue()   => ucfirst(BusinessRevenueSize::REVENUE_SIZE_1M_TO_5M()),
                BusinessRevenueSize::REVENUE_SIZE_ABOVE_5M()->getValue()   => ucfirst(BusinessRevenueSize::REVENUE_SIZE_ABOVE_5M()),
            ])
                ->rules('required')
                ->displayUsingLabels()
                ->onlyOnDetail(),

            Select::make('Team Size', 'team_size')->options([
                BusinessTeamSize::TEAM_SIZE_UNDER_10()->getValue() => ucfirst(BusinessTeamSize::TEAM_SIZE_UNDER_10()),
                BusinessTeamSize::TEAM_SIZE_11_TO_50()->getValue() => ucfirst(BusinessTeamSize::TEAM_SIZE_11_TO_50()),
                BusinessTeamSize::TEAM_SIZE_ABOVE_50()->getValue() => ucfirst(BusinessTeamSize::TEAM_SIZE_ABOVE_50()),
            ])
                ->rules('required')
                ->displayUsingLabels()
                ->onlyOnDetail(),

            AttachMany::make('Business Characteristics', 'characteristics')
                ->showCounts()
                ->fillUsing(function ($request, $model) {
                    return function () use ($model, $request) {
                        $memberIds = str_replace('[', '', $request->characteristics);
                        $memberIds = str_replace(']', '', $memberIds);
                        $memberIds = explode(',', $memberIds);
                        $members   = [];
                        foreach ($memberIds as $memberId) {
                            if ($memberId) {
                                $members[$memberId] = ['tag_id' => $memberId, 'tag_type' => TagType::BUSINESS_CHARACTERISTICS()];
                            }
                        }
                        $model->characteristics()->wherePivot('tag_type', TagType::BUSINESS_CHARACTERISTICS())->sync($members);
                    };
                })
                ->hideToolbar()
                ->height('300px')
                ->hideWhenCreating()
                ->showPreview(),

            Text::make('Business Characteristics', function () {
                $categories = $this->characteristics()->get();
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
                ->onlyOnDetail(),

            Select::make('Average Ticket Size Item(SGD)', 'average_ticket_size')->options([
                BusinessTicketSize::TICKET_SIZE_UNDER_10()->getValue()  => ucfirst(BusinessTicketSize::TICKET_SIZE_UNDER_10()),
                BusinessTicketSize::TICKET_SIZE_10_TO_50()->getValue()  => ucfirst(BusinessTicketSize::TICKET_SIZE_10_TO_50()),
                BusinessTicketSize::TICKET_SIZE_51_TO_100()->getValue() => ucfirst(BusinessTicketSize::TICKET_SIZE_51_TO_100()),
                BusinessTicketSize::TICKET_SIZE_ABOVE_100()->getValue() => ucfirst(BusinessTicketSize::TICKET_SIZE_ABOVE_100()),
            ])
                ->rules('required')
                ->displayUsingLabels()
                ->onlyOnDetail(),

            JSON::make('URLS', [
                Text::make('Website')
                    ->onlyOnDetail(),

                Text::make('Facebook')
                    ->onlyOnDetail(),

                Text::make('Instagram')
                    ->onlyOnDetail(),
            ])
                ->onlyOnDetail(),
        ]);
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
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function createCustomer()
    {
        return $this->merge([

            Text::make('First Name')
                ->rules('required', 'max:255')
                ->hideFromDetail()
                ->hideWhenUpdating()
                ->hideFromIndex(),

            Text::make('Last Name')
                ->rules('required', 'max:255')
                ->hideFromDetail()
                ->hideWhenUpdating()
                ->hideFromIndex(),

            Text::make('Email')
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')
                ->hideFromDetail()
                ->hideWhenUpdating()
                ->hideFromIndex(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6')
                ->hideFromIndex()
                ->hideWhenUpdating()
                ->hideFromDetail(),

            NestedForm::make('Profile')
                ->heading('Profile')
                ->hideWhenUpdating(),

            Select::make('Status')->options([
                CustomerStatus::INACTIVE()->getValue()         => ucfirst(CustomerStatus::INACTIVE()),
                CustomerStatus::ACTIVE()->getValue()           => ucfirst(CustomerStatus::ACTIVE()),
                CustomerStatus::EMAIL_UNVERIFIED()->getValue() => ucfirst(CustomerStatus::EMAIL_UNVERIFIED()),
            ])
                ->rules('required')
                ->displayUsingLabels()
                ->hideFromDetail()
                ->hideWhenUpdating()
                ->hideFromIndex()
                ->withMeta(['value' => CustomerStatus::ACTIVE()]),
        ]);
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

            ID::make()
                ->hideFromDetail()
                ->hideFromIndex(),

            Text::make('Full Name', function () {
                if ($this->company_name) {
                    return $this->first_name . ' ' . $this->last_name . '<br> ' . '(' . $this->company_name . ')';
                }
                else {
                    return $this->first_name . ' ' . $this->last_name;
                }
            })
                ->asHtml()
                ->onlyOnIndex(),

            Text::make('Contact', function () {
                return $this->profile_country_code . ' ' . $this->profile_contact_number . '<br> ' . $this->email;
            })
                ->asHtml()
                ->onlyOnIndex(),

            Text::make('Address', function () {
                return $this->street . '<br> ' . $this->postal_code . '<br> ' . $this->country;
            })
                ->asHtml()
                ->onlyOnIndex(),

            Text::make('Billing', function () {
                if ($this->billing_first_name) {
                    return $this->billing_first_name . ' ' . $this->billing_last_name . '<br> ' . $this->billing_country_code . ' ' . $this->billing_contact_number;
                }
                else {
                    return 'N/A';
                }
            })
                ->asHtml()
                ->onlyOnIndex(),

            Text::make('Last Booking', function () {
                $lastBooking = $this->bookings->first();
                if ($lastBooking) {
                    return $lastBooking->spaceName . '<br>' . $lastBooking->unitName;
                }
                else {
                    return 'N/A';
                }
            })
                ->asHtml()
                ->onlyOnIndex(),

            Text::make('Status', function () {
                return ucfirst($this->status);
            })
                ->onlyOnIndex(),

            $this->createCustomer(),

            (new Tabs('Customer Details', [

                new Panel(__('Personal Information'), [
                    $this->personalInformations(),
                ]),

                new Panel(__('Billing Information'), [
                    $this->billingInformations(),
                ]),

                new Panel(__('Business Information'), [
                    $this->businessInformations(),
                ]),

                new Panel(__('Bank Account'), [
                    $this->bankAccounts(),
                ]),
            ]))
                ->withToolbar(),

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
            new Filters\CustomerFilter(),
            new SpaceRelationshipFilter(),
            new UnitRelationshipFilter(),
        ];
    }

    /**
     * This query determines to show only Role::CUSTOMER role instances in Customer Menu.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::CUSTOMER);
        });
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
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function personalInformations()
    {
        return $this->merge([

            Text::make('First Name')
                ->rules('required', 'max:255')
                ->hideWhenCreating()
                ->hideFromIndex(),

            Text::make('Last Name')
                ->rules('required', 'max:255')
                ->hideWhenCreating()
                ->hideFromIndex(),

            Text::make('Email')
                ->readonly(true)
                ->hideWhenCreating()
                ->hideFromIndex(),

            NestedForm::make('Profile', 'profile')
                ->heading('Profile')
                ->hideWhenCreating(),

            Text::make('Contact Number', function () {
                return $this->profile_country_code . ' ' . $this->profile_contact_number;
            })
                ->onlyOnDetail(),

            $this->addressFields(''),

            Select::make('Status')->options([
                CustomerStatus::INACTIVE()->getValue()         => ucfirst(CustomerStatus::INACTIVE()),
                CustomerStatus::ACTIVE()->getValue()           => ucfirst(CustomerStatus::ACTIVE()),
                CustomerStatus::EMAIL_UNVERIFIED()->getValue() => ucfirst(CustomerStatus::EMAIL_UNVERIFIED()),
            ])
                ->rules('required')
                ->displayUsingLabels()
                ->withMeta(['value' => $this->status ?? CustomerStatus::ACTIVE()])
                ->hideWhenCreating()
                ->hideFromIndex()
                ->hideFromDetail(),

            Text::make('Status', function () {
                return ucfirst($this->status);
            })
                ->onlyOnDetail(),
        ]);
    }

    /**
     * This query determines to show Role::CUSTOMER User instances of the model may be attached to other resources.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::CUSTOMER);
        });
    }

}
