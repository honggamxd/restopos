<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_config', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_capital_expenditure_request', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_capital_expenditure_request_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_capital_expenditure_request_recipient', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_expenditure_request_footer', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_item', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_item_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_purchase_order', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_purchase_order_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_purchase_order_recipient', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_purchase_request', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_purchase_request_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_purchase_request_recipient', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_receiving_report', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_receiving_report_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_request_to_canvass', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_request_to_canvass_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_stock_issuance', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_stock_issuance_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('inventory_stock_issuance_recipient', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_accepted_order_cancellation', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_bill', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_bill_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_meal_type', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_menu', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_order', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_order_cancellation', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_order_cancellation_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_order_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_payment', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_server', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_table', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_table_customer', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_temp_bill', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('restaurant_temp_bill_detail', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });
        Schema::table('user', function (Blueprint $table) {
            //
            $table->index(['deleted_at']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_config', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_capital_expenditure_request', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_capital_expenditure_request_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_capital_expenditure_request_recipient', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_expenditure_request_footer', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_item', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_item_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_purchase_order', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_purchase_order_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_purchase_order_recipient', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_purchase_request', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_purchase_request_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_purchase_request_recipient', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_receiving_report', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_receiving_report_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_request_to_canvass', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_request_to_canvass_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_stock_issuance', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_stock_issuance_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('inventory_stock_issuance_recipient', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_accepted_order_cancellation', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_bill', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_bill_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_meal_type', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_menu', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_order', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_order_cancellation', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_order_cancellation_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_order_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_payment', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_server', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_table', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_table_customer', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_temp_bill', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('restaurant_temp_bill_detail', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
        Schema::table('user', function (Blueprint $table) {
            //
            $table->dropIndex(['deleted_at']);
        });
    }
}
