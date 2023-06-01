<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponForRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_for_restaurants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restaurant_id')->index()->unsigned();
            $table->bigInteger('coupon_group_id')->index()->unsigned();
            $table->timestamps();

//            $table->foreign('coupon_group_id')
//                ->references('id')
//                ->on('coupon_groups')
//                ->onDelete('cascade');
//
//            $table->foreign('restaurant_id')
//                ->references('id')
//                ->on('restaurants')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_for_restaurants');
    }
}
