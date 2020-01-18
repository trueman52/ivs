<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpacesTable extends Migration
{

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spaces');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spaces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code', 5)->unique();
            $table->text('description')->nullable();
            $table->text('highlights')->nullable();
            $table->unsignedBigInteger('in_charge')->nullable();
            $table->foreign('in_charge')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->text('urls')->nullable();
            $table->text('announcement')->nullable();
            $table->unsignedBigInteger('tracking_category_id')->nullable();
            $table->text('remarks')->nullable();
            $table->text('internal_notes')->nullable();
            $table->enum('status', \App\Enums\SpaceStatus::toArray());
            $table->boolean('needs_curation')->default(0);
            $table->string('display_date', 30)->nullable();
            $table->date('booking_closing_at');
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

}
