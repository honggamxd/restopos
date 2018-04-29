<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryUserPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_user_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();

            $table->boolean('can_view_items')->unsigned();
            $table->boolean('can_add_items')->unsigned();
            $table->boolean('can_edit_items')->unsigned();
            $table->boolean('can_delete_items')->unsigned();

            $table->boolean('can_view_purchase_requests')->unsigned();
            $table->boolean('can_add_purchase_requests')->unsigned();
            $table->boolean('can_edit_purchase_requests')->unsigned();
            $table->boolean('can_delete_purchase_requests')->unsigned();
            $table->boolean('can_approve_purchase_requests')->unsigned();

            $table->boolean('can_view_request_to_canvasses')->unsigned();
            $table->boolean('can_add_request_to_canvasses')->unsigned();
            $table->boolean('can_edit_request_to_canvasses')->unsigned();
            $table->boolean('can_delete_request_to_canvasses')->unsigned();

            $table->boolean('can_view_capital_expenditure_requests')->unsigned();
            $table->boolean('can_add_capital_expenditure_requests')->unsigned();
            $table->boolean('can_edit_capital_expenditure_requests')->unsigned();
            $table->boolean('can_delete_capital_expenditure_requests')->unsigned();
            $table->boolean('can_approve_capital_expenditure_requests')->unsigned();

            $table->boolean('can_view_purchase_orders')->unsigned();
            $table->boolean('can_add_purchase_orders')->unsigned();
            $table->boolean('can_edit_purchase_orders')->unsigned();
            $table->boolean('can_delete_purchase_orders')->unsigned();
            $table->boolean('can_approve_purchase_orders')->unsigned();

            $table->boolean('can_view_receiving_reports')->unsigned();
            $table->boolean('can_add_receiving_reports')->unsigned();
            $table->boolean('can_edit_receiving_reports')->unsigned();
            $table->boolean('can_delete_receiving_reports')->unsigned();

            $table->boolean('can_view_stock_issuances')->unsigned();
            $table->boolean('can_add_stock_issuances')->unsigned();
            $table->boolean('can_edit_stock_issuances')->unsigned();
            $table->boolean('can_delete_stock_issuances')->unsigned();
            $table->boolean('can_approve_stock_issuances')->unsigned();

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
        Schema::dropIfExists('inventory_user_permission');
    }
}
