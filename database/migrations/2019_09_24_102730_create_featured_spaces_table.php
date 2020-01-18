<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedSpacesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('featured_spaces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('space_id');
            $table->foreign('space_id')
                ->references('id')
                ->on('spaces')
                ->onDelete('cascade');
            $table->text('data')->nullable();
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('featured_spaces');
    }

}
