<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoothAssignmentsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booth_assignments');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booth_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_period_id');
            $table->foreign('booking_period_id')
                ->references('id')
                ->on('booking_periods')
                ->onDelete('cascade');
            $table->unsignedTinyInteger('quantity');
            $table->string('space_code', 10);
            $table->string('unit_code', 10);
            $table->string('period_code', 10);
            $table->date('from');
            $table->date('to');
            $table->text('allocated_booths');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
