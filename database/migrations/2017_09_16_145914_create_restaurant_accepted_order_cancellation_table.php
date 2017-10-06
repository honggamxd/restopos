<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantAcceptedOrderCancellationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('restaurant_accepted_order_cancellation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('restaurant_menu_id')->unsigned();
            $table->integer('restaurant_table_customer_id')->unsigned();
            $table->double('quantity',19,2);
            $table->double('price',19,2);
            $table->softDeletes();
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
        //
        Schema::dropIfExists('restaurant_accepted_order_cancellation');
    }
}
