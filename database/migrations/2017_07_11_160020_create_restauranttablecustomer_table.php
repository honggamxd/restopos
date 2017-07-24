<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestauranttablecustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_table_customer', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('server');
          $table->integer('date_time');
          $table->integer('pax');
          $table->boolean('has_order');
          $table->boolean('has_paid');
          $table->boolean('has_billed_out');
          $table->integer('restaurant_id');
          $table->integer('restaurant_table_id');
          $table->integer('restaurant_temp_bill_id');
          $table->boolean('has_bill');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_table_customer');
    }
}
