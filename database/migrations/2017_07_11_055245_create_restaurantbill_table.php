<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantbillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_bill', function (Blueprint $table) {
          $table->increments('id');
          $table->double('excess');
          $table->integer('check_number');
          $table->boolean('is_paid');
          $table->integer('date_');
          $table->integer('date_time');
          $table->integer('pax');
          $table->integer('server');
          $table->integer('cashier');
          $table->string('table_name');
          $table->integer('restaurant_id');
          $table->integer('restaurant_table_customer_id');
          $table->boolean('deleted');
          $table->text('deleted_comment');
          $table->integer('deleted_date');
          $table->text('guest_name');
          $table->text('room_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('restaurant_bill');
    }
}
