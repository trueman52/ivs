<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Period;
use App\Models\Space;
use App\Models\Unit;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Space::class, function (Faker $faker) {
    return [
        'name'               => $faker->name,
        'code'               => substr(Str::random(), 0, 5),
        'description'        => $faker->realText(),
        'highlights'         => [
            ['text' => $faker->sentence()],
            ['text' => $faker->sentence()],
            ['text' => $faker->sentence()],
        ],
        'needs_curation'     => $faker->randomElement([0, 1]),
        'announcement'       => $faker->realText(),
        'booking_closing_at' => (new Carbon())->addMonths(rand(1, 12)),
        'status'             => \App\Enums\SpaceStatus::PUBLISHED(),
        'remarks'            => $faker->realText(),
        'internal_notes'     => $faker->realText(),
        'urls'               => json_encode([
            'space_url'     => $faker->domainName,
            'facebook_url'  => $faker->domainName,
            'instagram_url' => $faker->domainName,
        ]),
    ];
});

$factory->afterCreating(Space::class, function ($space, $faker) {
    $count = rand(1, 6);

    factory(Unit::class, $count)->create(['space_id' => $space->id]);
    $space->periods()->saveMany(factory(Period::class, $count)->make());

    // Attach the periods data to each unit.
    foreach ($space->units as $unit) {
        $sync = [];

        foreach ($space->periods as $period) {
            $price = rand(100, 999) * 100; // store in cents
            $max   = rand(10, 30); // store in cents

            $sync[] = [
                'period_id'          => $period->id,
                'unit_price'         => $price,
                'max_quantity'       => $max,
                'remaining_quantity' => rand(10, 100),
            ];
        }

        $unit->periods()->sync($sync);
    }
});
