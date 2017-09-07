<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantMenuIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('restaurant_menu_ingredients', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('restaurant_menu_id')->unsigned();
          $table->integer('restaurant_inventory_id')->unsigned();
          $table->double('quantity');
          $table->boolean('restaurant_inventory_deleted');
          $table->boolean('inventory_item_deleted');
          $table->boolean('restaurant_menu_deleted');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('restaurant_menu_ingredients');
    }
}
