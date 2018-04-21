<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryPurchaseRequestRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_request_recipient', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->boolean('allow_approve')->unsigned();
            $table->boolean('notify_email')->unsigned();
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
        Schema::dropIfExists('inventory_purchase_request_recipient');
    }
}
