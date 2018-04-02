<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryStockIssuanceDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_stock_issuance_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_stock_issuance_id')->unsigned()->nullable();
            $table->integer('inventory_item_id')->unsigned()->nullable();
            $table->integer('quantity')->unsigned();
            $table->double('unit_price',19,2)->unsigned();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('inventory_stock_issuance_detail');
    }
}
