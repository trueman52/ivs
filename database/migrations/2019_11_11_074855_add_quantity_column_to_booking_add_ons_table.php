<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityColumnToBookingAddOnsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_add_ons', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_add_ons', function (Blueprint $table) {
            $table->unsignedSmallInteger('quantity')->after('purchased_at');
        });
    }
}
