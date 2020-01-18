<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onDelete('cascade');
            $table->unsignedInteger('retain_amount')->nullable();
            $table->unsignedInteger('refund_amount')->nullable();
            $table->string('type', 30);
            $table->string('refund_as', 30);
            $table->text('reason')->nullable();
            $table->string('status', 30);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
