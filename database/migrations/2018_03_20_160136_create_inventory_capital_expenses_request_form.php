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
        Schema::create('inventory_capital_expenses_request_form', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->timestamp('budget_date')->nullable();
            $table->integer('budget_number')->unsigned()->nullable();
            $table->string('budget_description')->nullable();
            $table->double('budget_amount',19,2)->unsigned();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->text('source_of_funds')->nullable();
            $table->text('brief_project_description')->nullable();
            $table->string('purpose')->nullable();
            $table->string('requested_by_name')->nullable();
            $table->timestamp('requested_by_date')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->timestamp('approved_by_date')->nullable();
            $table->string('verified_as_funded_by_name')->nullable();
            $table->timestamp('verified_as_funded_by_date')->nullable();
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
        Schema::dropIfExists('inventory_capital_expenses_request_form');
    }
}
