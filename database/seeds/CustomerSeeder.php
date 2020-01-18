<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{

    /**
     * Run the database seeds for Tag.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 5)->states('withProfile')->create();
    }
}
