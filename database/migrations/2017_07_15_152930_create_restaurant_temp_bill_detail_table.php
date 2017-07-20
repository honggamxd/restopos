<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantTempBillDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('restaurant_temp_bill_detail', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('restaurant_menu_id');
        $table->integer('quantity');
        $table->double('price');
        $table->string('special_order');
        $table->integer('restaurant_temp_bill_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('restaurant_temp_bill_detail');
    }
}
