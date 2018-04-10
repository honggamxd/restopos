<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryStockIssuance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_stock_issuance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->timestamp('stock_issuance_date')->nullable();
            $table->integer('stock_issuance_number')->unsigned()->nullable();
            $table->string('requesting_department')->nullable();
            $table->string('request_chargeable_to')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_tin')->nullable();
            $table->string('received_by_name')->nullable();
            $table->timestamp('received_by_date')->nullable();
            $table->string('issued_by_name')->nullable();
            $table->timestamp('issued_by_date')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->timestamp('approved_by_date')->nullable();
            $table->string('posted_by_name')->nullable();
            $table->timestamp('posted_by_date')->nullable();
            $table->integer('inventory_receiving_report_id')->unsigned()->nullable();
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
        Schema::dropIfExists('inventory_stock_issuance');
    }
}
