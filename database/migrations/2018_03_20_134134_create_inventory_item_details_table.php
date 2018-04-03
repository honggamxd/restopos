<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemDetailsTable extends Migration
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
            $table->integer('inventory_item_id')->unsigned()->nullable();
            $table->double('unit_cost',19,2)->unsigned();;
            $table->integer('quantity');
            $table->integer('inventory_receiving_report_id')->unsigned()->nullable();
            $table->integer('inventory_stock_issuance_id')->unsigned()->nullable();
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
