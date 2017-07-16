<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantTempBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_temp_bill', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('restaurant_table_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_temp_bill');
    }
    //^qFIqUt3ZLl3
    //putty
    //ssh password
}
