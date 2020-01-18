<?php

use App\Models\Discount;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(TagSeeder::class);

        if (env('APP_ENV') === 'local') {
            $this->call(SpaceSeeder::class);
            $this->call(CustomerSeeder::class);

            factory(Tag::class, 30)->create();
        }
    }

}
