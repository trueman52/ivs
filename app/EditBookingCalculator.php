<?php

namespace App;

use App\Enums\DiscountType;
use App\Models\ApplicableCoupon;
use App\Models\Booking;
use App\Models\BookingDiscount;
use App\Models\Coupon;
use App\Models\GroupedAddOn;
use App\Models\Period;
use App\Models\Unit;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EditBookingCalculator
{
    use InteractsWithEditedBooking;

    /**
     * Sequence of which the discounts are applied in.
     *
     * @var array
     */
    protected $discountSequence = [];

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $periods;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $groupedAddOns;

    /**
     * @var \App\Models\Unit
     */
    protected $unit;

    /**
     * @var \App\Models\Coupon
     */
    protected $coupon;

    /**
     * THe calculation result.
     *
     * @var array
     */
    protected $result = [];

    /**
     * The subtotal for the booking. (Discounted periods + add-ons).
     *
     * @var float
     */
    protected $subTotal;

    /**
     * The un-discounted period total.
     *
     * @var float
     */
    protected $periodsTotal = 0;

    /**
     * The adhoc items
     *
     * @var array
     */
    protected $adhocItems = [];

    /**
     * Total amount for adhoc items.
     *
     * @var int
     */
    protected $adhocItemsTotal = 0;

    /**
     * The discounted period total.
     *
     * @var float
     */
    protected $discountedPeriodsTotal;

    /**
     * The add-ons total
     *
     * @var float
     */
    protected $addOnsTotal = 0;

    /**
     * Information for the add-ons that has been added to this booking.
     *
     * @var array
     */
    protected $addOnCalculations = [];

    /**
     * Information for the periods that was selected for this booking.
     *
     * @var array
     */
    protected $periodsCalculation = [];

    /**
     * Information for the adhoc items that was added for this booking.
     *
     * @var array
     */
    protected $adhocCalculations = [];

    /**
     * Applied discounts information.
     *
     * @var array
     */
    protected $appliedDiscounts = [];

    /**
     * Applied coupon's information.
     *
     * @var null
     */
    protected $appliedCoupon = null;

    /**
     * @var float
     */
    protected $gst;

    /**
     * @var float
     */
    protected $grandTotal = 0;

    /**
     * BookingCalculator constructor.
     *
     * @param \App\Models\Booking            $booking
     * @param int                            $quantity      - Number of units.
     * @param \App\Models\Unit               $unit          - Unit model
     * @param \Illuminate\Support\Collection $periods       - Collection of App\Models\Period
     * @param \Illuminate\Support\Collection $groupedAddOns - Collection of App\Models\GroupedAddOn
     * @param \App\Models\ApplicableCoupon   $coupon
     * @param array                          $adhocItems
     */
    public function __construct(Booking $booking, int $quantity, Unit $unit, Collection $periods, Collection $groupedAddOns, ApplicableCoupon $coupon, array $adhocItems = [])
    {
        $this->booking       = $booking;
        $this->quantity      = $quantity;
        $this->unit          = $unit;
        $this->periods       = $periods;
        $this->groupedAddOns = $groupedAddOns;
        $this->coupon        = $coupon;
        $this->adhocItems    = $adhocItems;

        $this->setDiscountSequence();
    }

    /**
     * Store information on our calculated grouped add-on.
     *
     * @param \App\Models\GroupedAddOn $groupedAddOn
     * @param int                      $addOnTotal
     */
    protected function addCalculatedAddOn(GroupedAddOn $groupedAddOn, int $addOnTotal)
    {
        $this->addOnCalculations[] = [
            'total'    => $addOnTotal,
            'quantity' => (int)$groupedAddOn->quantity,
            'name'     => $groupedAddOn->name,
        ];
    }

    protected function addCalculatedAdhocItem(array $adhocItem, int $total)
    {
        $this->adhocCalculations[] = [
            'item'  => $adhocItem,
            'total' => $total,
        ];
    }

    /**
     * Store information on our calculated period.
     *
     * @param \App\Models\Period $period
     * @param int                $periodTotal
     */
    protected function addCalculatedPeriod(Period $period, int $periodTotal)
    {
        $this->periodsCalculation[] = [
            'date'     => "{$period->fromDate->format('d')} - {$period->toDate->format('d M Y')}",
            'quantity' => $this->quantity,
            'total'    => $periodTotal,
        ];
    }

    /**
     * Apply coupon discounts to periods where applicable
     *
     * @return void
     */
    public function applyCoupon()
    {
        // check if the current coupon we are trying to apply is associated to a booking.
        // if it is, we will have to calculate the discount based on the the used coupon.
        if ($this->booking->isUsingCoupon($this->coupon)) {
            $this->coupon = $this->getUsedCoupon();
        }

        // if this coupon is not the one that's associated to the booking, we will
        // need to ensure the coupon meets the application criteria.
        if ($this->coupon instanceof Coupon && !$this->canApplyCoupon()) return;

        $couponInformation                  = ['beforeDiscount' => $this->discountedPeriodsTotal];
        $this->discountedPeriodsTotal       = $this->coupon->apply($this->discountedPeriodsTotal);
        $couponInformation['afterDiscount'] = $this->discountedPeriodsTotal;
        $couponInformation['coupon']        = $this->coupon;
        $this->appliedCoupon                = $couponInformation;
    }

    /**
     * Apply discounts to periods where applicable.
     */
    protected function applyDiscounts()
    {
        $discounts                    = $this->booking->bookingDiscounts;
        $this->discountedPeriodsTotal = $this->periodsTotal;
        $discountedTotal              = $this->periodsTotal;

        if (!$discounts) return;

        // Apply discounts based on the order of discount sequence.
        foreach ($this->discountSequence as $sequence) {
            // Filter discounts so that we don't have to loop through all of them.
            $filteredDiscounts = $discounts->where('type', $sequence);

            $methodName = "apply" . Str::studly($sequence) . "Discount";

            // Apply and store discount information.
            foreach ($filteredDiscounts as $filteredDiscount) {
                $discountInformation                  = ['beforeDiscount' => $discountedTotal];
                $discountedTotal                      = $this->$methodName($discountedTotal, $filteredDiscount);
                $discountInformation['afterDiscount'] = $discountedTotal;

                if (!$this->discountApplied($discountInformation['beforeDiscount'], $discountInformation['afterDiscount'])) {
                    continue;
                }

                $discountInformation['discount'] = $filteredDiscount;
                $this->appliedDiscounts[]        = $discountInformation;
            }
        }

        $this->discountedPeriodsTotal = $discountedTotal;
    }

    /**
     * Apply discount to our total.
     *
     * @param float                       $amount
     * @param \App\Models\BookingDiscount $discount
     *
     * @return float
     */
    protected function applyLimitedTimeDiscount(float $amount, BookingDiscount $discount)
    {
        return $discount->applyLimitedTimeDiscount($amount);
    }

    /**
     * Apply periods quantity discount.
     *
     * @param float                       $amount
     * @param \App\Models\BookingDiscount $discount
     *
     * @return float
     */
    protected function applyPeriodDiscount(float $amount, BookingDiscount $discount)
    {
        return $discount->applyPeriodDiscount($amount, $this->periods->count());
    }

    /**
     * Apply unit quantity discount.
     *
     * @param float                       $amount
     * @param \App\Models\BookingDiscount $discount
     *
     * @return float
     */
    protected function applyQuantityDiscount(float $amount, BookingDiscount $discount)
    {
        return $discount->applyQuantityDiscount($amount, $this->quantity);
    }

    /**
     * Calculate bookings.
     */
    protected function calculate()
    {
        // Load information if they have not been loaded.
        $this->booking->loadMissing([
            'usedCoupon',
            'bookingPeriods',
            'bookingDiscounts',
            'bookingAddOns.addOn',
            'AdhocItems',
        ]);

        $this->calculatePeriods();
        $this->calculateAddOns();
        $this->applyDiscounts();
        $this->applyCoupon();
        $this->calculateAdhocItems();
        $this->calculateSubTotal();
        $this->calculateGst();
        $this->calculateGrandTotal();
    }

    /**
     * Calculates the sum of each add-on and adding them up.
     *
     * @return void
     */
    protected function calculateAddOns()
    {
        $addOnsTotal = 0;
        $periodCount = $this->periods->count();

        if (!$this->groupedAddOns) return;

        foreach ($this->groupedAddOns as $groupedAddOn) {
            if ($this->isGroupedAddOnAssociatedToBooking($groupedAddOn)) {
                // If this grouped add-on  was already associated to the booking, then we will
                // have to use the pricing at the point when the booking was created
                // for the calculation.

                $addOnTotal = $groupedAddOn->quantity * $this->getBookingGroupedAddOnPricing($groupedAddOn) * $periodCount;
            }
            else {
                // If it isn't we will use the grouped add-on's latest pricing.

                $addOnTotal = $groupedAddOn->quantity * $groupedAddOn->costPerUnit * $periodCount;
            }

            $addOnsTotal += $addOnTotal;

            $this->addCalculatedAddOn($groupedAddOn, $addOnTotal);
        }

        $this->addOnsTotal = $addOnsTotal;
    }

    public function calculateAdhocItems()
    {
        foreach ($this->adhocItems as $adhocItem) {
            if ($this->isAdhocItemAssociatedToBooking($adhocItem)) {
                // If this adhoc item was already associated to the booking, then we will
                // have to use the pricing at the point when the booking was created
                // for the calculation.

                $total = $this->getBookingAdhocPricing($adhocItem) * $adhocItem['quantity'];
            }
            else {
                // If it isn't we will use the period's latest pricing.

                $total = $adhocItem['amount'] * $adhocItem['quantity'];
            }

            $this->adhocItemsTotal += $total;

            $this->addCalculatedAdhocItem($adhocItem, $total);
        }
    }

    /**
     * Calculated grand total by adding subtotal, gst, and security deposits.
     *
     * @return void
     */
    protected function calculateGrandTotal()
    {
        $this->grandTotal = $this->subTotal + $this->gst + $this->unit->securityDeposit;
    }

    /**
     * Calculate gst based on subtotal
     *
     * @return void
     */
    protected function calculateGst()
    {
        // Since we are already calculating the subtotal in cents, when calculating
        // the gst, we should get rid of the remaining decimal places.
        $this->gst = (int)round($this->subTotal * AppSetting::get('gst') / 100);
    }

    /**
     * Calculates the sum of each period and adding them up.
     *
     * @return void
     */
    protected function calculatePeriods()
    {
        foreach ($this->periods as $period) {
            if ($this->isPeriodAssociatedToBooking($period)) {
                // If this period was already associated to the booking, then we will
                // have to use the pricing at the point when the booking was created
                // for the calculation.

                $total = $this->getBookingPeriodPricing($period) * $this->quantity;
            }
            else {
                // If it isn't we will use the period's latest pricing.

                $total = $period->pivot->unit_price * $this->quantity;
            }

            $this->periodsTotal += $total;

            $this->addCalculatedPeriod($period, $total);
        }
    }

    /**
     * Calculates booking's subtotal.
     */
    public function calculateSubTotal()
    {
        $this->subTotal = $this->discountedPeriodsTotal + $this->adhocItemsTotal + $this->addOnsTotal;
    }

    /**
     * Checks if the coupon supplied can be used.
     *
     * @return bool
     */
    protected function canApplyCoupon()
    {
        if (!$this->coupon->exists) {
            return false;
        }

        if (!$this->isCouponUsableBySpace()) return false;

        if (!$this->isCouponUsableByCustomer()) return false;

        return true;
    }

    /**
     * Checks if discount was successfully applied.
     * True if discount applied.
     *
     * @param $before
     * @param $after
     *
     * @return bool
     */
    protected function discountApplied($before, $after)
    {
        return $before != $after;
    }

    /**
     * @return array
     */
    public function getAddOnCalculations()
    {
        return $this->addOnCalculations;
    }

    /**
     * @return float
     */
    public function getAddOnsTotal()
    {
        return $this->addOnsTotal;
    }

    /**
     * @return array
     */
    protected function getAdhocItemCalculations()
    {
        return $this->adhocCalculations;
    }

    /**
     * @return mixed
     */
    public function getAdhocItemsTotal()
    {
        return $this->adhocItemsTotal;
    }

    /**
     * @return array
     */
    public function getAppliedCoupon()
    {
        return $this->appliedCoupon;
    }

    /**
     * @return array
     */
    public function getAppliedDiscounts()
    {
        return $this->appliedDiscounts;
    }

    /**
     * Get the pricing that was used at the point when the booking was created.
     *
     * @param array $item
     *
     * @return int
     */
    protected function getBookingAdhocPricing(array $item)
    {
        $item = $this->getBookingAdhocItem($item);

        return $item->amount;
    }

    /**
     * Get the pricing that was used at the point when the booking was created.
     *
     * @param \App\Models\GroupedAddOn $groupedAddOn
     *
     * @return int
     */
    public function getBookingGroupedAddOnPricing(GroupedAddOn $groupedAddOn)
    {
        $addOn = $this->getBookingAddOn($groupedAddOn);

        return $addOn->purchasedAt;
    }

    /**
     * Get the pricing that was used at the point when the booking was created.
     *
     * @param \App\Models\Period $period
     *
     * @return int
     */
    public function getBookingPeriodPricing(Period $period)
    {
        $bookingPeriod = $this->getBookingPeriod($period);

        return $bookingPeriod->purchasedAt;
    }

    /**
     * Perform calculation and return the results.
     *
     * @return array
     */
    public function getCalculations()
    {
        $this->calculate();

        return [
            'addOns'                 => [
                'calculations' => $this->getAddOnCalculations(),
                'total'        => $this->getAddOnsTotal(),
            ],
            'periods'                => [
                'calculations' => $this->getPeriodsCalculation(),
                'total'        => $this->getPeriodsTotal(),
            ],
            'appliedCoupon'          => $this->getAppliedCoupon(),
            'appliedDiscounts'       => $this->getAppliedDiscounts(),
            'adhocItems'             => [
                'calculations' => $this->getAdhocItemCalculations(),
                'total'        => $this->getAdhocItemsTotal(),
            ],
            'discountedPeriodsTotal' => $this->getDiscountedPeriodsTotal(),
            'gst'                    => [
                'amount'     => $this->getGst(),
                'percentage' => AppSetting::get('gst'),
            ],
            'securityDeposit'        => $this->getSecurityDeposit(),
            'subTotal'               => $this->getSubTotal(),
            'grandTotal'             => $this->getGrandTotal(),
        ];
    }

    /**
     * @return float
     */
    public function getDiscountedPeriodsTotal()
    {
        return $this->discountedPeriodsTotal;
    }

    /**
     * @return float
     */
    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    /**
     * @return float
     */
    public function getGst()
    {
        return $this->gst;
    }

    /**
     * Get unit's periods.
     *
     * @param array $periodIds
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPeriods(array $periodIds)
    {
        return $this->unit->periods()->wherePivotIn('period_id', $periodIds)->get();
    }

    /**
     * @return array
     */
    public function getPeriodsCalculation()
    {
        return $this->periodsCalculation;
    }

    /**
     * @return float
     */
    public function getPeriodsTotal()
    {
        return $this->periodsTotal;
    }

    /**
     * Get unit's security deposit.
     *
     * @return mixed
     */
    public function getSecurityDeposit()
    {
        return $this->unit->securityDeposit;
    }

    /**
     * @return float
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * Get unit details
     *
     * @param int $id
     *
     * @return \App\Models\Unit
     */
    protected function getUnit(int $id)
    {
        return Unit::find($id);
    }

    /**
     * Get the used coupon that was used for the booking.
     *
     * @return \App\Models\UsedCoupon
     */
    protected function getUsedCoupon()
    {
        return $this->booking->usedCoupon;
    }

    /**
     * Set order in which discounts are applied.
     */
    public function setDiscountSequence()
    {
        $this->discountSequence = [
            (string)DiscountType::LIMITED_TIME(),
            (string)DiscountType::PERIOD(),
            (string)DiscountType::QUANTITY(),
        ];
    }
}
