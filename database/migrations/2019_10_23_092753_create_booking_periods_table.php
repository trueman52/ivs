<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingPeriodsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_periods');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings');
            $table->unsignedBigInteger('period_id');
            $table->foreign('period_id')
                  ->references('id')
                  ->on('periods');
            $table->unsignedInteger('purchased_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
