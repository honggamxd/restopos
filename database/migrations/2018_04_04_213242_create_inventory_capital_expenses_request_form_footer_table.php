<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryCapitalExpensesRequestFormFooterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_expenditure_request_footer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_capital_expenditure_request_id')->unsigned()->nullable();
            $table->double('invoice_amount',19,2)->unsigned();
            $table->double('approved_budget',19,2)->unsigned();
            $table->integer('inventory_item_id')->unsigned()->nullable();
            $table->string('item_description')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('inventory_expenditure_request_footer');
    }
}
