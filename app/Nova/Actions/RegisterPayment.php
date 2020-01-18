<?php

namespace App\Nova\Actions;

use App\Enums\PaymentMethod;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class RegisterPayment extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('Amount(S$)', 'amount')
                ->rules('required'),
            Select::make('Method')
                ->options(PaymentMethod::toSelectOptions())
                ->rules('required'),
            Text::make('Reference number')
                ->rules('required'),
            Textarea::make('Note')
                ->rules('required'),
            File::make('Upload file'),
        ];
    }

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection    $models
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $payment = Payment::create([
                'booking_id'       => $model->id,
                'amount'           => $fields->amount * 100, // save as cents
                'method'           => $fields->method,
                'reference_number' => $fields->reference_number,
                'note'             => $fields->note,
            ]);

            if (isset($fields->upload_file)) {
                $payment->addMedia($fields->upload_file)->toMediaCollection();
            }
        }
    }
}
