<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\BillingDetail::class, function (Faker $faker) {
    $contact_number = [];
    $contact_number['code'] = substr($faker->e164PhoneNumber, 1, 2);
    $contact_number['number'] = substr($faker->e164PhoneNumber, 3);
    return [
        'first_name'     => $faker->firstName,
        'last_name'      => $faker->lastName,
        'email'          => $faker->email,
        'contact_number' => $contact_number,
        'company_name'   => $faker->company,
    ];
})->afterCreating(\App\Models\BillingDetail::class, function ($billingDetail, $faker) {
    $billingDetail->address()->save(factory(\App\Models\Address::class)->make());
});
