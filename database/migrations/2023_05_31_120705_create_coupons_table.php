<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->tinyInteger('status')->default(0);
            $table->integer('coupon_group_id');
            $table->string('discount_type');
            $table->decimal('discount', 8, 2);
            $table->decimal('min_order_price', 8, 2)->default(0);
            $table->integer('uses_quantity')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('code')->unique();
            $table->tinyInteger('is_notification')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
