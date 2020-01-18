<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAmountColumnToSignedInAdhocItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adhoc_items', function (Blueprint $table) {
            DB::statement("ALTER TABLE adhoc_items MODIFY COLUMN amount INTEGER NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adhoc_items', function (Blueprint $table) {
            DB::statement("ALTER TABLE adhoc_items MODIFY COLUMN amount MEDIUMINT UNSIGNED NOT NULL");
        });
    }
}
