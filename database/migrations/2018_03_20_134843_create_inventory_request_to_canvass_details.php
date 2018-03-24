<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryRequestToCanvassDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_request_to_canvass_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_request_to_canvass_id')->unsigned()->nullable();
            $table->integer('inventory_item_id')->unsigned()->nullable();
            $table->integer('quantity')->unsigned();
            $table->double('vendor_1_unit_price',19,2);
            $table->double('vendor_2_unit_price',19,2);
            $table->double('vendor_3_unit_price',19,2);
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
        Schema::dropIfExists('inventory_request_to_canvass_detail');
    }
}
