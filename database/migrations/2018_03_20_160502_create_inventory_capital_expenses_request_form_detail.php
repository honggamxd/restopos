<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryCapitalExpensesRequestFormDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_capital_expenses_request_form_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_capital_expenses_request_form_id')->unsigned()->nullable();
            $table->integer('inventory_item_id')->unsigned()->nullable();
            $table->integer('quantity')->unsigned();
            $table->double('unit_price',19,2)->unsigned();
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
        Schema::dropIfExists('inventory_capital_expenses_request_form_detail');
    }
}
