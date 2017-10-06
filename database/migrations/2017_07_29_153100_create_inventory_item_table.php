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
          $table->string('unit');
          $table->string('item_name');
          $table->double('cost_price',19,2);
          $table->boolean('deleted');
          $table->string('type');
          $table->integer('deleted_by')->unsigned();
          $table->text('deleted_comment');
          $table->integer('deleted_date');
          $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('inventory_item');
    }
}
