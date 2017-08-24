<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantorderdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_order_detail', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('restaurant_menu_id')->unsigned();
          $table->text('special_order');
          $table->integer('order_name');
          $table->integer('quantity');
          $table->double('price');
          $table->integer('restaurant_order_id')->unsigned();
          $table->integer('restaurant_id')->unsigned();
          $table->boolean('deleted');
          $table->integer('deleted_by')->unsigned();
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
        Schema::dropIfExists('restaurant_order_detail');
    }
}
