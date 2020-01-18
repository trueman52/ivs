<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddOnAddOnGroupTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_on_add_on_group');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_on_add_on_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('add_on_group_id');
            $table->foreign('add_on_group_id')
                ->references('id')
                ->on('add_on_groups')
                ->onDelete('cascade');
            $table->unsignedBigInteger('add_on_id');
            $table->foreign('add_on_id')
                ->references('id')
                ->on('add_ons')
                ->onDelete('cascade');
            $table->integer('min');
            $table->integer('max');
            $table->integer('cost_per_unit');
        });
    }
}
