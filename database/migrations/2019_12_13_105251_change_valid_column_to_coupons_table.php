<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeValidColumnToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            DB::statement("ALTER TABLE coupons MODIFY COLUMN valid_from DATE DEFAULT NULL");
            DB::statement("ALTER TABLE coupons MODIFY COLUMN valid_to DATE DEFAULT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            DB::statement("ALTER TABLE coupons MODIFY COLUMN valid_from DATE NOT NULL");
            DB::statement("ALTER TABLE coupons MODIFY COLUMN valid_to DATE NOT NULL");
        });
    }
}
