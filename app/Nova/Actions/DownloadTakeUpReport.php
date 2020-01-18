<?php

namespace App\Nova\Actions;

use App\Models\Booking;
use App\Models\BookingPeriod;
use App\Models\Period;
use App\Models\Space;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\ActionRequest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadTakeUpReport extends DownloadExcel implements FromQuery, WithMapping, WithStrictNullComparison
{
    /**
     * @var \Carbon\Carbon
     */
    protected $firstStartDate;

    /**
     * @var \Carbon\Carbon
     */
    protected $firstEndDate;

    /**
     * @var \Carbon\Carbon
     */
    protected $secondStartDate;

    /**
     * @var \Carbon\Carbon
     */
    protected $secondEndDate;

    /**
     * Calculates the unit's overall take-ups.
     *
     * @param \App\Models\Unit                         $unit
     * @param \Illuminate\Database\Eloquent\Collection $bookings
     *
     * @return array
     */
    protected function calculateOverallTakeUps(Unit $unit, Collection $bookings)
    {
        $bookings = $bookings->filter(function (Booking $booking) use ($unit) {
            return $booking->unitId === $unit->id;
        });

        $total      = $this->calculateOverallTotalSold($bookings);
        $percentage = $this->calculatePercentageSold($unit->periods->sum('pivot.max_quantity'), $total);

        return [
            'total'      => $total,
            'percentage' => $percentage,
        ];
    }

    /**
     * Calculate the number of units sold for all the periods.
     *
     * @param \Illuminate\Database\Eloquent\Collection $bookings
     *
     * @return int
     */
    protected function calculateOverallTotalSold(Collection $bookings)
    {
        return $bookings->sum(function (Booking $booking) {
            // Since the booking's quantity determines the quantity of periods,
            // we can safely use this value to calculate the number of units sold.
            return $booking->bookingPeriods->first()->quantity;
        });
    }

    /**
     * Calculates the percentage of units sold.
     *
     * @param int $maxQuantity
     * @param int $sold
     *
     * @return mixed
     */
    protected function calculatePercentageSold(int $maxQuantity, int $sold)
    {
        return $sold / $maxQuantity * 100;
    }

    /**
     * @param \App\Models\Period                       $period
     * @param \Illuminate\Database\Eloquent\Collection $bookings
     *
     * @return array
     */
    protected function calculatePeriodTakeUps(Period $period, Collection $bookings)
    {
        $bookingPeriods = $bookings->pluck('bookingPeriods')->collapse();

        $bookingPeriods = $bookingPeriods->filter(function (BookingPeriod $bookingPeriod) use ($period) {
            return $bookingPeriod->periodId === $period->id;
        });

        $total      = $this->calculatePeriodTotalSold($bookingPeriods);
        $percentage = $this->calculatePercentageSold($period->pivot->max_quantity, $total);

        return [
            'total'      => $total,
            'percentage' => $percentage,
        ];
    }

    /**
     * Calculates the total of units sold for a single period.
     *
     * @param \Illuminate\Support\Collection $bookingPeriods
     *
     * @return mixed
     */
    protected function calculatePeriodTotalSold(SupportCollection $bookingPeriods)
    {
        return $bookingPeriods->sum('quantity');
    }

    /**
     * @param string $date
     * @param string $format
     *
     * @return \Carbon\Carbon
     */
    protected function castFieldToCarbon(string $date, string $format = 'Y-m-d')
    {
        return Carbon::createFromFormat($format, $date);
    }

    /**
     * @return array|\Laravel\Nova\Fields\Field[]
     */
    public function fields()
    {
        return [
            Date::make('First date')
                ->help('The first comparable date to be used to filter periods'),
            Date::make('Second date')
                ->help('The second comparable date to be used to filter periods'),
        ];
    }

    /**
     * @param int            $spaceId
     * @param \Carbon\Carbon $start
     * @param \Carbon\Carbon $end
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getComparableBookings(int $spaceId, Carbon $start, Carbon $end)
    {
        return Booking::with(['bookingPeriods', 'unit'])
            ->where('space_id', $spaceId)
            ->createdBetweenDates(['start' => $start, 'end' => $end])
            ->get();
    }

    /**
     * @param ActionRequest $request
     * @param Action        $exportable
     *
     * @return array
     */
    public function handle(ActionRequest $request, Action $exportable): array
    {
        $this->firstStartDate = $this->castFieldToCarbon($request->first_date)->startOfDay();
        $this->firstEndDate   = $this->firstStartDate->copy();

        $this->firstEndDate->addDays(6)->endOfDay();

        $this->secondStartDate = $this->castFieldToCarbon($request->second_date)->startOfDay();
        $this->secondEndDate   = $this->secondStartDate->copy();

        $this->secondEndDate->addDays(6)->endOfDay();

        $response = Excel::download($exportable, $this->getFilename(), $this->getWriterType());

        if (!$response instanceof BinaryFileResponse || $response->isInvalid()) {
            return \is_callable($this->onFailure)
                ? ($this->onFailure)($request, $response)
                : Action::danger(__('Resource could not be exported.'));
        }

        return \is_callable($this->onSuccess)
            ? ($this->onSuccess)($request, $response)
            : Action::download(
                $this->getDownloadUrl($response),
                $this->getFilename()
            );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|mixed $space
     *
     * @return array
     */
    public function map($space): array
    {
        // First get all the space's units and periods.
        $space->loadMissing('units.periods');

        $firstComparableBookings  = $this->getComparableBookings($space->id, $this->firstStartDate, $this->firstEndDate);
        $secondComparableBookings = $this->getComparableBookings($space->id, $this->secondStartDate, $this->secondEndDate);

        $rows[] = $this->spaceHeading($space);

        foreach ($space->units as $unit) {
            $firstOverallTakeUps  = $this->calculateOverallTakeUps($unit, $firstComparableBookings);
            $secondOverallTakeUps = $this->calculateOverallTakeUps($unit, $secondComparableBookings);

            $rows[] = [
                $unit->name,
                $unit->max_quantity,
                $firstOverallTakeUps['total'],
                $firstOverallTakeUps['percentage'],
                $secondOverallTakeUps['total'],
                $secondOverallTakeUps['percentage'],
                $secondOverallTakeUps['percentage'] - $firstOverallTakeUps['percentage'],
            ];

            foreach ($unit->periods as $period) {
                $firstPeriodTakeUps  = $this->calculatePeriodTakeUps($period, $firstComparableBookings);
                $secondPeriodTakeUps = $this->calculatePeriodTakeUps($period, $secondComparableBookings);

                $rows[] = [
                    "{$period->fromDate} - {$period->toDate}",
                    $period->pivot->max_quantity,
                    $firstPeriodTakeUps['total'],
                    $firstPeriodTakeUps['percentage'],
                    $secondPeriodTakeUps['total'],
                    $secondPeriodTakeUps['percentage'],
                    $secondPeriodTakeUps['percentage'] - $firstPeriodTakeUps['percentage'],
                ];
            }
        }

        return $rows;
    }

    /**
     * @param \App\Models\Space $space
     *
     * @return array
     */
    protected function spaceHeading(Space $space)
    {
        return [
            $space->name,
            'Max Q',
            "{$this->firstStartDate} - {$this->firstEndDate} (sold)",
            "{$this->firstStartDate} - {$this->firstEndDate} (%)",
            "{$this->secondStartDate} - {$this->secondEndDate} (sold)",
            "{$this->secondStartDate} - {$this->secondEndDate} (%)",
            'Change (%)',
        ];
    }
}
