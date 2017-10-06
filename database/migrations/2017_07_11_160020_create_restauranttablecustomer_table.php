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
          $table->integer('server_id')->unsigned();;
          $table->string('table_name');
          $table->text('guest_name');
          $table->integer('date_time');
          $table->integer('pax');
          $table->integer('sc_pwd');
          $table->boolean('has_order');
          $table->boolean('has_paid');
          $table->boolean('has_billed_out');
          $table->boolean('has_billed_completely');
          $table->integer('restaurant_id')->unsigned();
          $table->integer('restaurant_table_id')->unsigned();
          $table->integer('restaurant_temp_bill_id')->unsigned();
          $table->boolean('has_bill');
          $table->boolean('has_order_cancelled');
          $table->integer('cancellation_order_status');
          $table->timestamps();
        });
    }
    // cancellation_order_status
    //0 = did not cancelled any orders
    //1 = has cancelled orders
    //2 = cancelled orders have been settled

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
