<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_item_detail', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('inventory_item_id')->unsigned();
          $table->double('quantity',19,2);
          $table->double('cost_price',19,2);
          $table->integer('date_');
          $table->integer('date_time');
          $table->string('remarks');
          $table->string('reference_table');
          $table->integer('reference_id')->unsigned();
          $table->integer('user_id')->unsigned();
          $table->boolean('deleted');
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
        Schema::dropIfExists('inventory_item_detail');
    }
}
