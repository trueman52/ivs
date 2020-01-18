<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to see if customer can update business profile
     *
     * @return void
     */
    public function test_customer_can_create_bank_detail()
    {
        $this->withoutExceptionHandling();

        $user  = factory(User::class)->create();
        $faker = \Faker\Factory::create();

        $response = $this->actingAs($user)->json('post', 'web/my-bank', [
            'bankName'       => $faker->company,
            'bankCode'       => rand(100, 999),
            'accountType'    => $faker->word,
            'accountNumber'  => $faker->bankAccountNumber,
            'branchCode'     => $faker->word,
            'holderName'     => $faker->name,
            'accountAddress' => $faker->address,
        ]);


        $response->assertStatus(200);

        $this->assertDatabaseHas('bank_details', ['user_id' => $user->id]);
    }

    /**
     * Test to see if customer can retrieve business profile.
     *
     * @return void
     */
    public function test_customer_can_delete_bank_details()
    {
        $user = factory(User::class)->state('withBank')->create();

        $this->assertDatabaseHas('bank_details', ['user_id' => $user->id]);

        $response = $this->actingAs($user)->json('delete', 'web/my-bank');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('bank_details', ['user_id' => $user->id]);
    }

    /**
     * Test to see if customer can see their own bank details.
     */
    public function test_customer_can_get_bank_details()
    {
        $user     = factory(User::class)->state('withBank')->create();
        $response = $this->actingAs($user)->json('get', 'web/my-bank');
        $bank     = json_decode($response->getContent(), true)['bank'];

        $this->assertNotEmpty($bank['id']);
    }
}
