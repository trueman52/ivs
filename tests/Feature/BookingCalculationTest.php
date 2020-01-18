<?php

namespace Tests\Feature;

use App\AppSetting;
use App\Enums\CouponStatus;
use App\Enums\DiscountRateType;
use App\Enums\DiscountStatus;
use App\Enums\DiscountType;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\GroupedAddOn;
use App\Models\Space;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BookingCalculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\Space
     */
    protected $space;

    /**
     * @var \App\Models\Unit
     */
    protected $unit;

    /**
     * @var
     */
    protected $unitPeriod;

    /**
     * @var
     */
    protected $adhocItems;

    /**
     * Get grouped add-ons randomly from add-on groups
     *
     * @param \Illuminate\Support\Collection $addOnGroups
     *
     * @return \Illuminate\Support\Collection
     */
    protected function get_grouped_add_ons_randomly(Collection $addOnGroups)
    {
        // Get add-ons that's available only to this unit
        $groupedAddOns = GroupedAddOn::whereIn('add_on_group_id', $addOnGroups->pluck('id'))->get();

        return $groupedAddOns->random(2);
    }

    public function get_selected_group_add_ons_in_api_format(Collection $selectedGroupAddOns, int $quantity)
    {
        return $selectedGroupAddOns->map(function ($item) use ($quantity) {
            return [
                'id'       => $item->id,
                'quantity' => $quantity,
            ];
        })->toArray();
    }

    /**
     * @param \App\Enums\DiscountType $type
     * @param \App\Models\Unit        $unit
     *
     * @return mixed
     */
    public function make_discount(DiscountType $type, Unit $unit)
    {
        $type     = (string)$type;
        $rateType = DiscountRateType::PERCENTAGE();

        switch ($type) {
            case DiscountType::QUANTITY():
                $data = [
                    'no_of_units' => 2,
                    'rate'        => 3,
                    'rate_type'   => $rateType,
                ];
                break;
            case DiscountType::PERIOD():
                $data = [
                    'no_of_periods' => 1,
                    'rate'          => 2,
                    'rate_type'     => $rateType,
                ];
                break;
            default:
                $data = [
                    'rate'       => 1,
                    'rate_type'  => $rateType,
                    'start_date' => Carbon::now(),
                    'end_date'   => (Carbon::now())->addWeeks(3),
                ];
        }

        $discount =  Discount::create([
            'name'    => "{$type} discount ({$data['rate']} {$rateType})",
            'type'    => $type,
            'data'    => json_encode($data),
            'status'  => (string)DiscountStatus::ACTIVE(),
        ]);

        $unit->discounts()->attach($discount->id);

        return $discount;
    }

    protected function make_random_space_data()
    {
        $this->space      = factory(Space::class)->create();
        $this->unit       = $this->space->units->random();
        $this->unitPeriod = $this->unit->periods->first()->pivot;
    }

    /**
     * Test add-ons total are added correctly.
     */
    public function test_add_ons_calculations()
    {
        $this->make_random_space_data();

        $addOnGroups         = $this->unit->addOnGroups;
        $selectedGroupAddOns = $this->get_grouped_add_ons_randomly($addOnGroups);
        $addOnsQuantity      = 2;
        $response            = $this->json('get', 'api/calculate-booking', [
            'quantity'      => 1,
            'unitId'        => $this->unit->id,
            'periodUnitIds' => [$this->unitPeriod->id],
            'groupedAddOns' => json_encode($this->get_selected_group_add_ons_in_api_format($selectedGroupAddOns, $addOnsQuantity)),
        ]);
        $responseData        = json_decode($response->getContent(), true);
        $addOnsTotal         = 0;

        foreach ($selectedGroupAddOns as $addOn) {
            $addOnsTotal += $addOn->cost_per_unit * $addOnsQuantity;
        }

        $this->assertEquals($responseData['addOns']['total'], $addOnsTotal);
    }

    /**
     * Test ad-hoc items total are added correctly.
     */
    public function test_adhoc_calculations()
    {
        $this->make_random_space_data();

        $response = $this->json('get', 'api/calculate-booking', [
            'quantity'      => 1,
            'unitId'        => $this->unit->id,
            'periodUnitIds' => [$this->unitPeriod->id],
            'adhocItems'    => json_encode([
                ['name' => 'Item 1', 'amount' => 5000, 'quantity' => 2],
                ['name' => 'Item 2', 'amount' => 2000, 'quantity' => 1],
            ]),
        ]);

        $content = json_decode($response->getContent(), true);

        $this->assertEquals($content['adhocItems']['total'], 12000);
    }

    /**
     * Test discounts and coupons are applied to periods only.
     */
    public function test_discounts_and_coupons_are_applied_to_periods_only()
    {
        $this->make_random_space_data();

        $periodQuantity         = 2;
        $limitedTimeDiscount    = $this->make_discount(DiscountType::LIMITED_TIME(), $this->unit);
        $unitQuantityDiscount   = $this->make_discount(DiscountType::QUANTITY(), $this->unit);
        $periodQuantityDiscount = $this->make_discount(DiscountType::PERIOD(), $this->unit);

        // Create a coupon and override certain data to make it more predictable.
        $coupon                  = factory(Coupon::class)->create(['status' => (string)CouponStatus::ACTIVE()]);
        $couponData              = $coupon->data;
        $couponData['rate']      = 4;
        $couponData['rate_type'] = (string)DiscountRateType::PERCENTAGE();
        $coupon->data            = $couponData;
        $coupon->save();

        $response = $this->json('get', 'api/calculate-booking', [
            'quantity'      => $periodQuantity,
            'unitId'        => $this->unit->id,
            'periodUnitIds' => [$this->unitPeriod->id],
            'couponCode'    => $coupon->code,
        ]);

        $responseData = json_decode($response->getContent(), true);

        // calculate periods cost multiply by quantity
        $periodTotal = $this->unitPeriod->unit_price * $periodQuantity;

        // Apply discounts according to business requirements.
        $discountedAmount = (int)round($periodTotal * (100 - $limitedTimeDiscount->data['rate']) / 100);
        $discountedAmount = (int)round($discountedAmount * (100 - $periodQuantityDiscount->data['rate']) / 100);
        $discountedAmount = (int)round($discountedAmount * (100 - $unitQuantityDiscount->data['rate']) / 100);

        // Apply coupon
        $discountedAmount = (int)round($discountedAmount * (100 - $coupon->data['rate']) / 100);

        // Check discount is applied in the correct sequence
        $this->assertEquals($responseData['discountedPeriodsTotal'], $discountedAmount);
    }

    /**
     * Test the grand total amount is added correctly.
     */
    public function test_grand_total_calculations()
    {
        $this->make_random_space_data();

        $periodQuantity = 2;

        $response = $this->json('get', 'api/calculate-booking', [
            'quantity'      => $periodQuantity,
            'unitId'        => $this->unit->id,
            'periodUnitIds' => [$this->unitPeriod->id],
        ]);

        $responseData = json_decode($response->getContent(), true);

        $total = $responseData['subTotal'] * (AppSetting::get('gst') + 100) / 100 + $this->unit->securityDeposit;

        $this->assertEquals($responseData['grandTotal'], $total);
    }

    /**
     * Test periods total are added correctly.
     */
    public function test_periods_calculations()
    {
        $this->make_random_space_data();
        $periodQuantity = 2;

        $response = $this->json('get', 'api/calculate-booking', [
            'quantity'      => $periodQuantity,
            'unitId'        => $this->unit->id,
            'periodUnitIds' => [$this->unitPeriod->id],
        ]);

        $responseData = json_decode($response->getContent(), true);

        // calculate periods cost multiply by quantity
        $periodTotal = $this->unitPeriod->unit_price * $periodQuantity;

        // Check periods total matches
        $this->assertEquals($responseData['periods']['total'], $periodTotal);
    }

    /**
     * Test service charge calculations are calculated correctly.
     */
    public function test_service_charge_calculation()
    {
        $this->make_random_space_data();
        $periodQuantity = 2;

        $response = $this->json('get', 'api/calculate-booking', [
            'quantity'      => $periodQuantity,
            'unitId'        => $this->unit->id,
            'periodUnitIds' => [$this->unitPeriod->id],
        ]);

        $responseData = json_decode($response->getContent(), true);

        $gst = $responseData['subTotal'] * AppSetting::get('gst') / 100;

        $this->assertEquals($responseData['gst']['amount'], $gst);
    }
}
