<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryPuchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->timestamp('purchase_order_date')->nullable();
            $table->integer('purchase_order_number')->unsigned()->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_tin')->nullable();
            $table->string('term')->nullable();
            $table->string('requesting_department')->nullable();
            $table->string('purpose')->nullable();
            $table->string('request_chargeable_to')->nullable();
            $table->string('requested_by_name')->nullable();
            $table->timestamp('requested_by_date')->nullable();
            $table->string('noted_by_name')->nullable();
            $table->timestamp('noted_by_date')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->timestamp('approved_by_date')->nullable();
            $table->integer('inventory_receiving_report_id')->unsigned()->nullable();
            $table->integer('inventory_purchase_request_id')->unsigned()->nullable();
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
        Schema::dropIfExists('inventory_purchase_order');
    }
}
