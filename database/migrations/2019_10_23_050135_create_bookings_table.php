<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('space_id');
            $table->foreign('space_id')
                  ->references('id')
                  ->on('spaces')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')
                  ->references('id')
                  ->on('units')
                  ->onDelete('cascade');
            $table->unsignedInteger('grand_total');
            $table->unsignedInteger('paid');
            $table->unsignedInteger('deposit');
            $table->json('data');
            $table->text('remarks', 1000)->nullable();
            $table->text('internal_notes', 1000)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
