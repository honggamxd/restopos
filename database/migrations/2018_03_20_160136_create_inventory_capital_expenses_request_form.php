<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryCapitalExpensesRequestForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_capital_expenditure_request', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->timestamp('capital_expenditure_request_date')->nullable();
            $table->integer('capital_expenditure_request_number')->unsigned()->nullable();
            $table->string('budget_description')->nullable();
            $table->double('budget_amount',19,2)->unsigned();
            $table->string('department')->nullable();
            $table->text('source_of_funds')->nullable();
            $table->text('brief_project_description')->nullable();
            $table->string('purpose')->nullable();
            $table->string('requested_by_name')->nullable();
            $table->timestamp('requested_by_date')->nullable();
            $table->string('requested_by_position')->nullable();
            $table->string('approved_by_1_name')->nullable();
            $table->timestamp('approved_by_1_date')->nullable();
            $table->string('approved_by_1_position')->nullable();
            $table->string('approved_by_2_name')->nullable();
            $table->timestamp('approved_by_2_date')->nullable();
            $table->string('approved_by_2_position')->nullable();
            $table->string('verified_as_funded_by_name')->nullable();
            $table->timestamp('verified_as_funded_by_date')->nullable();
            $table->string('verified_as_funded_by_position')->nullable();
            $table->string('recorded_by_name')->nullable();
            $table->timestamp('recorded_by_date')->nullable();
            $table->string('recorded_by_position')->nullable();
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
        Schema::dropIfExists('inventory_capital_expenditure_request');
    }
}
