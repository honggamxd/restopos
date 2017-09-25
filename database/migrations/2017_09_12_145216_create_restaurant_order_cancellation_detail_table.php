<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantOrderCancellationDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('restaurant_order_cancellation_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('restaurant_order_cancellation_id')->unsigned();
            $table->integer('restaurant_menu_id')->unsigned();
            $table->double('quantity');
            $table->double('price');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('restaurant_order_cancellation_detail');
    }
}
