<?php

namespace Tests\Feature;

use App\Models\Space;
use App\Models\User;
use Tests\TestCase;

class AdminCreateBookingTest extends TestCase
{
    /**
     * @var
     */
    protected $space;

    /**
     * @var
     */
    protected $unit;

    /**
     * @var
     */
    protected $groupedAddOns;

    /**
     * @var
     */
    protected $unitPeriod;

    public function make_space()
    {
        $this->space         = factory(Space::class)->create();
        $this->unit          = $this->space->units->random();
        $this->unitPeriod    = $this->unit->periods->first()->pivot;
        $this->groupedAddOns = $this->unit->addOnGroups->first()->groupedAddOns;
        $this->groupedAddOn  = $this->groupedAddOns->first();
    }

    /**
     * Make a valid booking api call so we can test the results.
     *
     * @param \App\Models\User $admin
     * @param \App\Models\User $customer
     * @param int              $quantity
     *
     * @return mixed
     */
    public function  make_valid_booking_api_call(User $admin, User $customer, $quantity = 1)
    {
        $response = $this->actingAs($admin)
            ->json('post', '/nova-vendor/create-booking-tool/bookings', [
                'userId'        => $customer->id,
                'quantity'      => $quantity,
                'unitId'        => $this->unit->id,
                'periodUnitIds' => [$this->unitPeriod->id],
                'adhocItems'    => json_encode([
                    ['name' => 'adhoc item 1', 'amount' => 50000, 'quantity' => $quantity],
                ]),
                'groupedAddOns' => json_encode([
                    ['id' => $this->groupedAddOn->id, 'quantity' => $this->groupedAddOn->min],
                ]),
            ]);

        return $response;
    }


    public function test_admin_can_create_booking()
    {
        $this->make_space();

        $admin    = factory(User::class)->state('superAdmin')->create();
        $customer = factory(User::class)->states('customer')->create();
        $response = $this->make_valid_booking_api_call($admin, $customer);
        $content  = json_decode($response->getContent(), true);

        $this->assertDatabaseHas('bookings', ['id' => $content['booking']['id']]);
    }

    public function test_after_booking_has_been_created_periods_quantity_is_reduced()
    {
        $this->make_space();

        $admin = factory(User::class)->state('superAdmin')->create();

        $customer = factory(User::class)->state('customer')->create();
        $quantity = $this->unitPeriod->remaining_quantity;

        $this->make_valid_booking_api_call($admin, $customer, 2);

        $this->unitPeriod->refresh();

        $this->assertEquals($this->unitPeriod->remaining_quantity, $quantity - 2);
    }

    public function test_booking_periods_have_been_created_after_booking_creation()
    {
        $this->make_space();

        $quantity = 2;
        $admin    = factory(User::class)->state('superAdmin')->create();
        $customer = factory(User::class)->state('customer')->create();
        $response = $this->make_valid_booking_api_call($admin, $customer, $quantity);
        $content  = json_decode($response->getContent(), true);

        $this->assertDatabaseHas('booking_periods', [
            'booking_id'   => $content['booking']['id'],
            'quantity'     => $quantity,
            'purchased_at' => $this->unitPeriod->unit_price,
        ]);
    }

    public function test_booking_add_ons_have_been_created_after_booking_creation()
    {
        $this->make_space();

        $quantity = 2;
        $admin    = factory(User::class)->state('superAdmin')->create();
        $customer = factory(User::class)->state('customer')->create();
        $response = $this->make_valid_booking_api_call($admin, $customer, $quantity);
        $content  = json_decode($response->getContent(), true);

        $this->assertDatabaseHas('booking_add_ons', [
            'booking_id'             => $content['booking']['id'],
            'add_on_add_on_group_id' => $this->groupedAddOn->id,
            'quantity'               => $this->groupedAddOn->min,
            'purchased_at'           => $this->groupedAddOn->cost_per_unit,
        ]);
    }

    public function test_booking_adhoc_items_have_been_created_after_booking_creation()
    {
        $this->make_space();

        $quantity = 2;
        $admin    = factory(User::class)->state('superAdmin')->create();
        $customer = factory(User::class)->state('customer')->create();
        $response = $this->make_valid_booking_api_call($admin, $customer, $quantity);
        $content  = json_decode($response->getContent(), true);

        $this->assertDatabaseHas('adhoc_items', [
            'booking_id' => $content['booking']['id'],
            'name'       => 'adhoc item 1',
            'amount'     => 50000,
            'quantity'   => $quantity,
        ]);
    }

    public function test_booking_details_are_persisted()
    {
        $this->make_space();

        $quantity = 2;
        $admin    = factory(User::class)->state('superAdmin')->create();
        $customer = factory(User::class)->state('customer')->create();
        $response = $this->make_valid_booking_api_call($admin, $customer, $quantity);
        $content  = json_decode($response->getContent(), true);

        $this->assertDatabaseHas('bookings', ['id' => $content['booking']['id']]);
    }

    public function test_customer_cannot_create_booking_through_backend_api()
    {
        $this->make_space();

        $customer = factory(User::class)->states('customer')->create();
        $response = $this->make_valid_booking_api_call($customer, $customer);

        $response->assertStatus(403);
    }

    public function test_booking_cannot_be_created_when_add_on_min_quantity_is_unmet()
    {
        $this->make_space();
        $admin    = factory(User::class)->state('superAdmin')->create();
        $customer = factory(User::class)->state('customer')->create();

        $response = $this->actingAs($admin)
            ->json('post', '/nova-vendor/create-booking-tool/bookings', [
                'userId'        => $customer->id,
                'quantity'      => 1,
                'unitId'        => $this->unit->id,
                'periodUnitIds' => [$this->unitPeriod->id],
                'adhocItems'    => json_encode([]),
                'groupedAddOns' => json_encode([
                    ['id' => $this->groupedAddOn->id, 'quantity' => $this->groupedAddOn->min - 1],
                ]),
            ]);

        $response->assertStatus(422);
    }

    public function test_booking_cannot_be_created_when_period_unit_quantity_is_insufficient()
    {
        $this->make_space();
        $admin    = factory(User::class)->state('superAdmin')->create();
        $customer = factory(User::class)->state('customer')->create();

        $response = $this->actingAs($admin)
            ->json('post', '/nova-vendor/create-booking-tool/bookings', [
                'userId'        => $customer->id,
                'quantity'      => $this->unitPeriod->remaining_quantity + 1,
                'unitId'        => $this->unit->id,
                'periodUnitIds' => [$this->unitPeriod->id],
                'adhocItems'    => json_encode([]),
                'groupedAddOns' => json_encode([]),
            ]);

        $response->assertStatus(422);
    }
}
