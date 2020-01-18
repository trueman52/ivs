<?php

use App\Enums\CouponStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 10)->unique();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users');
            $table->unsignedBigInteger('created_for')->nullable();
            $table->foreign('created_for')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('space_id')->nullable();
            $table->foreign('space_id')
                ->references('id')
                ->on('spaces')
                ->onDelete('cascade');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->text('data');
            $table->enum('status', CouponStatus::toArray());
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
