<?php

namespace App\Nova\Actions;

use App\Models\BoothAssignment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class DownloadBookingReport extends DownloadExcel implements WithMapping, WithStrictNullComparison
{
    /**
     * @var
     */
    protected $booking;

    /**
     * @param Collection $bookingAddOns
     *
     * @return string
     */
    protected function getBookingAddOnInformation(Collection $bookingAddOns): string
    {
        $names = '';

        foreach ($bookingAddOns as $bookingAddOn) {
            $names .= "{$bookingAddOn->addOn->frontendName} x {$bookingAddOn->quantity}\n";
        }

        return $names;
    }

    /**
     * @param \App\Models\BoothAssignment $assignment
     *
     * @return string
     */
    protected function getBoothAssignmentCodes(?BoothAssignment $assignment): string
    {
        $codes = '';

        if (!$assignment) return $codes;

        foreach ($assignment->allocated_booths as $allocation) {
            $codes .= "{$assignment->getBoothCode($allocation['booth_number'])}\n";
        }

        return $codes;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Booking #',
            'Customer details',
            'Space',
            'Periods',
            'Assignments',
            'Add-ons',
            'Total payable',
            'Amount paid',
            'GST',
            'Status',
        ];
    }

    protected function loadRelations(): void
    {
        $this->booking->loadMissing([
            'customerDetail',
            'space',
            'bookingPeriods.period',
            'bookingPeriods.boothAssignments',
            'bookingAddOns.addOn',
        ]);
    }

    /**
     * @param $booking
     *
     * @return array
     */
    public function map($booking): array
    {
        $this->booking = $booking;

        $this->loadRelations();

        $rows = [];

        foreach ($this->booking->bookingPeriods as $key => $bookingPeriod) {
            $assignmentCodes = $this->getBoothAssignmentCodes($bookingPeriod->boothAssignments->first());

            $rows[] = [
                !$key ? $this->booking->createdAt : '',
                !$key ? $this->booking->id : '',
                !$key ? "{$this->booking->customerDetail->name}\n{$this->booking->customerDetail->email}\n{$this->booking->customerDetail->contactNumber}" : '',
                !$key ? $this->booking->space->name : '',
                "{$bookingPeriod->period->date_detail} x {$bookingPeriod->quantity} Unit",
                $assignmentCodes ?: 'N/A',
                $this->getBookingAddOnInformation($this->booking->bookingAddOns),
                $this->booking->outstanding,
                $this->booking->paid,
                $this->booking->gst,
                $this->booking->status,
            ];
        }

        return $rows;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Download booking report';
    }
}
