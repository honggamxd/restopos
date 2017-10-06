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
        $table->integer('restaurant_menu_id')->unsigned();
        $table->integer('quantity');
        $table->double('price',19,2);
        $table->string('restaurant_menu_name');
        $table->string('special_instruction');
        $table->integer('restaurant_temp_bill_id')->unsigned();
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
      Schema::dropIfExists('restaurant_temp_bill_detail');
    }
}
