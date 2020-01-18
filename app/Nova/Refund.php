<?php

namespace App\Nova;

use App\Enums\RefundOption;
use App\Enums\RefundStatus;
use App\Enums\RefundType;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Yassi\NestedForm\NestedForm;

class Refund extends Resource
{
    use HasDependencies, HasCustomLinkInField;

    public static $with = [
        'customer.bank',
        'customer.profile',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Refund';

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
            ID::make()->sortable(),

            BelongsTo::make('Booking', 'booking')->exceptOnForms(),

            Text::make('Customer', function () {
                $html = $this->generateLink("/admin/resources/customers/{$this->customer->id}", $this->customer->fullName);
                $html .= "<span class='text-sm'><br>{$this->customer->email}<br>{$this->customer->profile->full_contact_number}</span>";

                return $html;
            })->asHtml(),

            Text::make('Bank', function () {
                $html = "<div class='text-sm'>{$this->customer->bank_name} - {$this->customer->account_type}";
                $html .= "<br>{$this->customer->account_number}";
                $html .= "<br>{$this->customer->holder_name}</div>";

                return $html;
            })->asHtml(),

            Select::make('Refund type', 'type')
                ->options(RefundType::toSelectOptions())
                ->rules('required'),

            NovaDependencyContainer::make([
                Text::make('Retain amount', 'retain_amount')->rules('numeric'),
            ])
                ->dependsOn('type', RefundType::SD_REFUND()->getValue()),

            NovaDependencyContainer::make([
                Text::make('Refund amount', 'refund_amount')->rules('numeric'),
            ])
                ->dependsOn('type', RefundType::PAYMENT_REFUND()->getValue()),

            Number::make('Retain amount', 'retain_amount')->onlyOnIndex(),

            Number::make('Refund amount', 'refund_amount')->onlyOnIndex(),

            Textarea::make('Reason')
                ->rules('required'),

            Select::make('Refund option', 'refund_as')
                ->rules('required')
                ->options(RefundOption::toSelectOptions()),

            Select::make('Status')
                ->withMeta(['value' => $this->status ?: (string)RefundStatus::PENDING_APPROVAL()])
                ->options(RefundStatus::toSelectOptions())
                ->rules('required'),

            NestedForm::make('Coupon')->displayIf(function () {
                return [
                    [
                        'attribute' => 'refund_as',
                        'is'        => RefundOption::COUPON()->getValue(),
                    ],
                ];
            }),
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
