<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryPuchaseRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_request', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->timestamp('purchase_request_date')->nullable();
            $table->integer('purchase_request_number')->unsigned()->nullable();
            $table->string('requesting_department')->nullable();
            $table->string('reason_for_the_request')->nullable();
            $table->string('request_chargeable_to')->nullable();
            $table->string('type_of_item_requested')->nullable();
            $table->timestamp('date_needed')->nullable();
            $table->string('requested_by_name')->nullable();
            $table->timestamp('requested_by_date')->nullable();
            $table->string('noted_by_name')->nullable();
            $table->timestamp('noted_by_date')->nullable();
            $table->boolean('is_approved');
            $table->string('approved_by_name')->nullable();
            $table->timestamp('approved_by_date')->nullable();
            $table->integer('inventory_receiving_report_id')->unsigned()->nullable();
            $table->integer('inventory_capital_expenses_request_form_id')->unsigned()->nullable();
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
        Schema::dropIfExists('inventory_purchase_request');
    }
}
