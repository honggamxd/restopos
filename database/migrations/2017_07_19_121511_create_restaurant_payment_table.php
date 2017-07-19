<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_payment', function (Blueprint $table) {
          $table->increments('id');
          $table->double('payment');
          $table->string('settlement');
          $table->integer('restaurant_id');
          $table->integer('date_');
          $table->integer('date_time');
          $table->integer('restaurant_bill_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_payment');
    }
}
