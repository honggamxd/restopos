<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateInventorySystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('inventory_item');
        Schema::dropIfExists('inventory_item_detail');
        Schema::dropIfExists('issuance');
        Schema::dropIfExists('issuance_detail');
        Schema::dropIfExists('issuance_to');
        Schema::dropIfExists('purchase');
        Schema::dropIfExists('purchase_detail');
        Schema::dropIfExists('purchase_detail');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
