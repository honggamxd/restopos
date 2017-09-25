<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantOrderCancellationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('restaurant_order_cancellation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('settlement');
            $table->integer('resturant_order_id')->unsigned();
            $table->integer('restaurant_table_customer_id')->unsigned();
            $table->integer('restaurant_id')->unsigned();
            $table->integer('cancelled_by')->unsigned();
            $table->integer('approved_by')->unsigned();
            $table->boolean('approved');
            $table->text('reason_cancelled');
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
        Schema::dropIfExists('restaurant_order_cancellation');
    }
}
