<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddOnGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('add_on_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('backend_name', 100);
            $table->string('frontend_name', 100)->nullable();
            $table->enum('status', \App\Enums\AddOnGroupStatus::toArray());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('add_on_groups');
    }

}
