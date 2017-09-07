<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('restaurant_inventory', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('inventory_item_id')->unsigned();
          $table->double('quantity');
          $table->string('ref_table');
          $table->integer('ref_id')->unsigned();
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
        //
        Schema::dropIfExists('restaurant_inventory');
    }
}
