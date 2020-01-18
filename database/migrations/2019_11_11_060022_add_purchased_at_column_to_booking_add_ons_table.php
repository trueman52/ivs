<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchasedAtColumnToBookingAddOnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_add_ons', function (Blueprint $table) {
            $table->unsignedInteger('purchased_at')->after('add_on_add_on_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_add_ons', function (Blueprint $table) {
            $table->dropColumn('purchased_at');
        });
    }
}
