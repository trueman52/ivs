<?php

namespace App\Nova\Actions;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class DownloadCustomerReport extends DownloadExcel implements WithMapping, WithStrictNullComparison
{
    /**
     * @var
     */
    protected $customer;

    public function headings(): array
    {
        return [
            'Name',
            'Contact',
            'Company name',
            'Address',
            'Latest booking',
            'Status',
        ];
    }

    /**
     * @param $customer
     *
     * @return array
     */
    public function map($customer): array
    {
        $this->customer = $customer;
        $billing        = 'N/A';
        $latestBooking  = 'N/A';

        if ($customer->billing_first_name) {
            $billing = "{$customer->billing_first_name} {$customer->billing_last_name}\n{$customer->billing_country_code} {$customer->billing_contact_number}";
        }

        if ($customer->bookings()->count()) {
            $booking       = $customer->bookings()->first();
            $latestBooking = "{$booking->spaceName}\n{$booking->unitName}";
        }


        return [
            "{$customer->first_name} {$customer->last_name} ({$customer->company_name})",
            "{$customer->profile_country_code} {$customer->profile_contact_number}\n{$customer->email}",
            "{$customer->street}\n{$customer->postal_code}\n{$customer->country}",
            $billing,
            $latestBooking,
            $customer->status,
        ];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Download customer report';
    }
}
