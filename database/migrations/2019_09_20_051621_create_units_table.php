<?php

use App\Enums\UnitStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('space_id');
            $table->foreign('space_id')
                ->references('id')
                ->on('spaces')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('code', 5)->unique();
            $table->text('description');
            $table->text('additional_information')->nullable();
            $table->unsignedInteger('security_deposit')->default(0);
            $table->text('remarks')->nullable();
            $table->text('internal_notes')->nullable();
            $table->enum('status', UnitStatus::toArray());
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
