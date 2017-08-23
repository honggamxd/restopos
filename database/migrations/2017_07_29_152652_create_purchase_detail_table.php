<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_detail', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('inventory_item_id')->unsigned();
          $table->double('quantity');
          $table->double('cost_price');
          $table->integer('date_');
          $table->boolean('deleted');
          $table->integer('purchase_id')->unsigned();
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
        Schema::dropIfExists('purchase_detail');
    }
}
