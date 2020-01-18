<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeContactNumberFieldToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('contact_number', 50)->nullable()->change();
        });
        
        Schema::table('billing_details', function (Blueprint $table) {
            $table->string('contact_number', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('contact_number', 30)->nullable()->change();
        });
        
        Schema::table('billing_details', function (Blueprint $table) {
            $table->string('contact_number', 30)->change();
        });
    }
}
