<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryReceivingReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_receiving_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->timestamp('receiving_report_date')->nullable();
            $table->integer('receiving_report_number')->unsigned()->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_tin')->nullable();
            $table->string('supplier_contact_number')->nullable();
            $table->string('term')->nullable();
            $table->string('requesting_department')->nullable();
            $table->string('purpose')->nullable();
            $table->string('request_chargeable_to')->nullable();
            $table->string('received_by_name')->nullable();
            $table->timestamp('received_by_date')->nullable();
            $table->string('checked_by_name')->nullable();
            $table->timestamp('checked_by_date')->nullable();
            $table->string('posted_by_name')->nullable();
            $table->timestamp('posted_by_date')->nullable();
            $table->integer('inventory_purchase_order_id')->unsigned()->nullable();
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
        Schema::dropIfExists('inventory_receiving_report');
    }
}
