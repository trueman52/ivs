<?php

use App\Enums\TagType;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{

    /**
     * Run the database seeds for Tag.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Tag For Space Tag
         */

        Tag::updateOrCreate(
            [
                'name' => 'Trending',
                'type' => TagType::TAG(),
            ],
            [
                'name'       => 'Trending',
                'type'       => TagType::TAG(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        Tag::updateOrCreate(
            [
                'name' => 'Selling First',
                'type' => TagType::TAG(),
            ],
            [
                'name'       => 'Selling First',
                'type'       => TagType::TAG(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        Tag::updateOrCreate(
            [
                'name' => 'Booking Close',
                'type' => TagType::TAG(),
            ],
            [
                'name'       => 'Booking Close',
                'type'       => TagType::TAG(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }

}
