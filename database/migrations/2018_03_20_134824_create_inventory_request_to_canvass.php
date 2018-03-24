<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryRequestToCanvass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_request_to_canvass', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_to_canvass_number')->unsigned()->nullable();
            $table->timestamp('request_to_canvass_date');
            $table->string('uuid')->nullable();
            $table->string('requesting_department')->nullable();
            $table->text('reason_for_the_request')->nullable();
            $table->string('type_of_item_requested')->nullable();
            $table->integer('inventory_purchase_request_id')->unsigned()->nullable();
            $table->string('requested_by_name')->nullable();
            $table->timestamp('requested_by_date')->nullable();
            $table->string('noted_by_name')->nullable();
            $table->timestamp('noted_by_date')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->timestamp('approved_by_date')->nullable();
            $table->integer('inventory_capital_expenses_request_form_id')->unsigned()->nullable();
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
        Schema::dropIfExists('inventory_request_to_canvass');
    }
}
