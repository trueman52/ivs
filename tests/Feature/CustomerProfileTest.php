<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to see if customer can retrieve their own details through api endpoint.
     *
     * @return void
     */
    public function test_customer_can_retrieve_own_profile_information()
    {
        $user          = factory(User::class)->create();
        $response      = $this->actingAs($user)->json('get', 'web/me');
        $retrievedUser = json_decode($response->getContent())->user;

        $this->assertEquals($retrievedUser->id, $user->id);
    }

    /**
     * Test to see if user can update their profile information
     *
     * @return void
     */
    public function testCustomerCanUpdateOwnProfileInformation()
    {
        $this->withoutExceptionHandling();

        $user        = factory(User::class)->states('withProfile', 'withBilling')->create();
        $faker       = \Faker\Factory::create();
        $firstName   = $faker->firstName;
        $companyName = $faker->company;
        $street      = $faker->streetAddress;
        $countryCode = '65';
        $phoneNumber = '98765432';

        $response = $this->actingAs($user)->json('put', 'web/me', [
            'user'                 => [
                'firstName' => $firstName,
                'lastName'  => $user->lastName,
            ],
            'profile'              => [
                'code'          => $countryCode,
                'number'        => $phoneNumber,
                'companyName'   => $companyName,
            ],
            'profileAddress'       => [
                'country'    => $user->profile->address->country,
                'street'     => $street,
                'postalCode' => $user->profile->address->postalCode,
            ],
            'billingDetail'        => [
                'firstName'     => $firstName,
                'lastName'      => $user->billing->lastName,
                'code'          => $countryCode,
                'number'        => $phoneNumber,
                'email'         => $user->billing->email,
                'companyName'   => $user->billing->companyName,
            ],
            'billingDetailAddress' => [
                'country'    => $user->billing->address->country,
                'street'     => $street,
                'postalCode' => $user->billing->address->postalCode,
                'city'       => $user->billing->address->city,
                'state'      => $user->billing->address->state,
            ],
        ]);

        $response->assertStatus(200);

        $user->refresh();

        $this->assertEquals($user->firstName, $firstName);
        $this->assertEquals($user->profile->companyName, $companyName);
        $this->assertEquals($user->profile->address->street, $street);
        $this->assertEquals($user->billing->firstName, $firstName);
        $this->assertEquals($user->billing->address->street, $street);
    }
}
