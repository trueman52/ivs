<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCouponTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to see if customer retrieve their valid coupons details.
     *
     * @return void
     */
    public function test_customer_can_retrieve_received_coupons()
    {
        $couponsCount = 5;
        $coupons      = factory(Coupon::class, $couponsCount)->make();
        $user         = factory(User::class)->create();

        $user->receivedCoupons()->saveMany($coupons);
        $this->assertEquals($user->receivedCoupons->count(), $couponsCount);

        $response = $this->actingAs($user)->json('get', 'web/my-coupons');
        $coupons  = json_decode($response->getContent(), true)['coupons'];

        $response->assertStatus(200);
        $this->assertEquals(sizeof($coupons), $user->receivedCoupons()->valid()->count());
    }
}
