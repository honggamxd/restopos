<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantbilldetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_bill_detail', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('restaurant_menu_id');
          $table->text('special_order');
          $table->integer('quantity');
          $table->double('price');
          $table->integer('restaurant_bill_id');
          $table->integer('restaurant_id');
          $table->boolean('deleted');
          $table->text('deleted_comment');
          $table->integer('deleted_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_bill_detail');
    }
}
