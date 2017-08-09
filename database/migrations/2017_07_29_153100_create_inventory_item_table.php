<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_item', function (Blueprint $table) {
          $table->increments('id');
          $table->string('category');
          $table->string('subcategory');
          $table->string('item_name');
          $table->double('cost_price');
          $table->boolean('deleted');
          $table->integer('deleted_by')->unsigned();
          $table->text('deleted_comment');
          $table->integer('deleted_date');
          $table->integer('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_item');
    }
}
