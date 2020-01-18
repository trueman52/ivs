<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\BankDetail::class, function (Faker $faker) {
    return [
        'bank_name'       => $faker->company,
        'bank_code'       => rand(100, 999),
        'account_type'    => $faker->word,
        'account_number'  => $faker->bankAccountNumber,
        'branch_code'     => $faker->word,
        'holder_name'     => $faker->name,
        'location'        => $faker->country,
        'account_address' => $faker->address,
    ];
});
