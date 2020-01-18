<?php

namespace Tests\Feature;

use App\Enums\BusinessAgeSize;
use App\Enums\BusinessRevenueSize;
use App\Enums\BusinessTeamSize;
use App\Enums\BusinessTicketSize;
use App\Enums\TagType;
use App\Models\BusinessDetail;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to see if customer can retrieve business profile.
     *
     * @return void
     */
    public function test_customer_can_retrieve_own_business_information()
    {
        $user     = factory(User::class)->state('withBusiness')->create();
        $response = $this->actingAs($user)->json('get', 'web/my-business-detail');
        $business = json_decode($response->getContent())->business;

        $this->assertEquals($business->userId, $user->id);
    }

    /**
     * Test to see if customer can update business profile
     */
    public function test_customer_can_update_own_business_information()
    {
        $tags                 = factory(Tag::class, 30)->create();
        $characteristics      = $tags->where('type', TagType::BUSINESS_CHARACTERISTICS());
        $characteristicsCount = rand(0, sizeof($characteristics));
        $user                 = factory(User::class)->states('withBusiness')->create();
        $faker                = \Faker\Factory::create();

        // Data we are using to update.
        $age             = $faker->randomElement(BusinessAgeSize::toArray());
        $revenue         = $faker->randomElement(BusinessRevenueSize::toArray());
        $teamSize        = $faker->randomElement(BusinessTeamSize::toArray());
        $ticketSize      = $faker->randomElement(BusinessTicketSize::toArray());
        $characteristics = $characteristics->random($characteristicsCount)->pluck('id')->all();

        $this->actingAs($user)->json('put', 'web/my-business-detail', [
            'age'               => $age,
            'revenue'           => $revenue,
            'teamSize'          => $teamSize,
            'averageTicketSize' => $ticketSize,
            'tags'              => $characteristics,
        ]);

        $business = BusinessDetail::where('user_id', $user->id)->first();

        $this->assertEquals($business->age, $age);
        $this->assertEquals($business->revenue, $revenue);
        $this->assertEquals($business->teamSize, $teamSize);
        $this->assertEquals($business->averageTicketSize, $ticketSize);
        $this->assertEquals($business->businessCharacteristics->pluck('id')->all(), $characteristics);
    }
}
