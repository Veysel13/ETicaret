<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_status_id')->unsigned();
            $table->string("fullname")->nullable();
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->string("phone2")->nullable();
            $table->decimal('total_price',10,4);
            $table->string("description",30)->nullable();
            $table->string("bank",30)->nullable();
            $table->integer("installment_count")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
