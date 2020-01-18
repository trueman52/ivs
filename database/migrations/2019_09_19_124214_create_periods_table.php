<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periods');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('space_id');
            $table->foreign('space_id')
                ->references('id')
                ->on('spaces')
                ->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('code', 5)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

}
