<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{Favourite, User, Space};
use Faker\Generator as Faker;

$factory->define(Favourite::class, function (Faker $faker) {
    $user  = factory(User::class)->create();
    $space = factory(Space::class)->create();

    return [
        'space_id' => $space->id,
        'user_id'  => $user->id,
    ];
});
