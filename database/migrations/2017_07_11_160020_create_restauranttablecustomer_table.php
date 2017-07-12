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
          $table->integer('restaurant_id');
          $table->integer('restaurant_table_id');
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
