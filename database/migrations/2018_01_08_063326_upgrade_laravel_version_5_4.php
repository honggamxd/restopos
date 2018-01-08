<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeLaravelVersion54 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('username')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('privilege')->nullable()->change();
            $table->text('deleted_comment')->nullable()->change();
        });

        Schema::table('restaurant_menu', function (Blueprint $table) {
          $table->string('category')->nullable()->change();;
          $table->string('subcategory')->nullable()->change();;
          $table->string('name')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('restaurant_order', function (Blueprint $table) {
          $table->string('table_name')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('restaurant', function (Blueprint $table) {
          $table->string('name')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('restaurant_bill', function (Blueprint $table) {
          $table->string('table_name')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
          $table->text('guest_name')->nullable()->change();;
          $table->text('room_number')->nullable()->change();;
        });

        Schema::table('restaurant_order_detail', function (Blueprint $table) {
          $table->text('special_instruction')->nullable()->change();;
          $table->string('restaurant_menu_name')->nullable()->change();;
          $table->string('table_name')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('restaurant_table', function (Blueprint $table) {
          $table->string('name')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('restaurant_bill_detail', function (Blueprint $table) {
          $table->string('restaurant_menu_name')->nullable()->change();;
          $table->text('special_instruction')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('restaurant_table_customer', function (Blueprint $table) {
          $table->string('table_name')->nullable()->change();;
          $table->text('guest_name')->nullable()->change();;
        });

        Schema::table('restaurant_temp_bill', function (Blueprint $table) {
        });

        Schema::table('restaurant_temp_bill_detail', function (Blueprint $table) {
          $table->string('restaurant_menu_name')->nullable()->change();;
          $table->string('special_instruction')->nullable()->change();;
        });

        Schema::table('restaurant_payment', function (Blueprint $table) {
          $table->string('settlement')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('app_config', function (Blueprint $table) {
          $table->string('settlements')->nullable()->change();;
          $table->string('categories')->nullable()->change();;
          $table->string('version')->nullable()->change();;
        });

        Schema::table('purchase', function (Blueprint $table) {
          $table->string('po_number')->nullable()->change();;
          $table->text('comments')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('purchase_detail', function (Blueprint $table) {
        });

        Schema::table('inventory_item', function (Blueprint $table) {
          $table->string('category')->nullable()->change();;
          $table->string('unit')->nullable()->change();;
          $table->string('item_name')->nullable()->change();;
          $table->string('type')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('inventory_item_detail', function (Blueprint $table) {
          $table->string('remarks')->nullable()->change();;
          $table->string('reference_table')->nullable()->change();;
        });

        Schema::table('issuance', function (Blueprint $table) {
          $table->string('issuance_number')->nullable()->change();;
          $table->text('comments')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('issuance_detail', function (Blueprint $table) {
        });

        Schema::table('restaurant_server', function (Blueprint $table) {
          $table->string('name')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('issuance_to', function (Blueprint $table) {
          $table->string('name')->nullable()->change();;
          $table->string('ref_table')->nullable()->change();;
          $table->text('deleted_comment')->nullable()->change();;
        });

        Schema::table('restaurant_order_cancellation', function (Blueprint $table) {
            $table->string('settlement')->nullable()->change();;
            $table->text('reason_cancelled')->nullable()->change();;
        });

        Schema::table('restaurant_order_cancellation_detail', function (Blueprint $table) {
        });

        Schema::table('restaurant_accepted_order_cancellation', function (Blueprint $table) {
        });

        Schema::table('restaurant_bill', function ($table) {
        });

        Schema::table('restaurant_order', function ($table) {
        });
        Schema::table('restaurant_bill', function ($table) {
            $table->string('type')->nullable()->change();;
        });
        Schema::table('restaurant_order_cancellation', function ($table) {
            $table->string('type')->nullable()->change();;
        });
        Schema::table('restaurant_accepted_order_cancellation', function ($table) {
            $table->string('settlement')->nullable()->change();;
        });

        Schema::table('restaurant_table_customer', function ($table) {
        });

        Schema::table('app_config', function ($table) {
            $table->string('badorder_settlements')->nullable()->change();;
            $table->string('settlements_arrangements')->nullable()->change();;
        });

        Schema::table('restaurant_bill', function (Blueprint $table) {
            $table->string('reason_cancelled')->nullable()->change();;
        });

        Schema::table('restaurant_accepted_order_cancellation', function (Blueprint $table) {
            $table->string('reason_cancelled')->nullable()->change();;
        });

        Schema::table('restaurant_bill', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->change();;
        });

        Schema::table('user', function (Blueprint $table) {
        });

        Schema::table('restaurant_meal_type', function (Blueprint $table) {
            $table->string('type')->nullable()->change();;
            $table->time('schedule')->nullable()->change();;
        });

        Schema::table('restaurant_bill', function (Blueprint $table) {
            $table->string('meal_type')->nullable()->change();;
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('username')->change();
            $table->string('name')->change();
            $table->string('password')->change();
            $table->string('privilege')->change();
            $table->text('deleted_comment')->change();
        });

        Schema::table('restaurant_menu', function (Blueprint $table) {
          $table->string('category')->change();;
          $table->string('subcategory')->change();;
          $table->string('name')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('restaurant_order', function (Blueprint $table) {
          $table->string('table_name')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('restaurant', function (Blueprint $table) {
          $table->string('name')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('restaurant_bill', function (Blueprint $table) {
          $table->string('table_name')->change();;
          $table->text('deleted_comment')->change();;
          $table->text('guest_name')->change();;
          $table->text('room_number')->change();;
        });

        Schema::table('restaurant_order_detail', function (Blueprint $table) {
          $table->text('special_instruction')->change();;
          $table->string('restaurant_menu_name')->change();;
          $table->string('table_name')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('restaurant_table', function (Blueprint $table) {
          $table->string('name')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('restaurant_bill_detail', function (Blueprint $table) {
          $table->string('restaurant_menu_name')->change();;
          $table->text('special_instruction')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('restaurant_table_customer', function (Blueprint $table) {
          $table->string('table_name')->change();;
          $table->text('guest_name')->change();;
        });

        Schema::table('restaurant_temp_bill', function (Blueprint $table) {
        });

        Schema::table('restaurant_temp_bill_detail', function (Blueprint $table) {
          $table->string('restaurant_menu_name')->change();;
          $table->string('special_instruction')->change();;
        });

        Schema::table('restaurant_payment', function (Blueprint $table) {
          $table->string('settlement')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('app_config', function (Blueprint $table) {
          $table->string('settlements')->change();;
          $table->string('categories')->change();;
          $table->string('version')->change();;
        });

        Schema::table('purchase', function (Blueprint $table) {
          $table->string('po_number')->change();;
          $table->text('comments')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('purchase_detail', function (Blueprint $table) {
        });

        Schema::table('inventory_item', function (Blueprint $table) {
          $table->string('category')->change();;
          $table->string('unit')->change();;
          $table->string('item_name')->change();;
          $table->string('type')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('inventory_item_detail', function (Blueprint $table) {
          $table->string('remarks')->change();;
          $table->string('reference_table')->change();;
        });

        Schema::table('issuance', function (Blueprint $table) {
          $table->string('issuance_number')->change();;
          $table->text('comments')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('issuance_detail', function (Blueprint $table) {
        });

        Schema::table('restaurant_server', function (Blueprint $table) {
          $table->string('name')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('issuance_to', function (Blueprint $table) {
          $table->string('name')->change();;
          $table->string('ref_table')->change();;
          $table->text('deleted_comment')->change();;
        });

        Schema::table('restaurant_order_cancellation', function (Blueprint $table) {
            $table->string('settlement')->change();;
            $table->text('reason_cancelled')->change();;
        });

        Schema::table('restaurant_order_cancellation_detail', function (Blueprint $table) {
        });

        Schema::table('restaurant_accepted_order_cancellation', function (Blueprint $table) {
        });

        Schema::table('restaurant_bill', function ($table) {
        });

        Schema::table('restaurant_order', function ($table) {
        });
        Schema::table('restaurant_bill', function ($table) {
            $table->string('type')->change();;
        });
        Schema::table('restaurant_order_cancellation', function ($table) {
            $table->string('type')->change();;
        });
        Schema::table('restaurant_accepted_order_cancellation', function ($table) {
            $table->string('settlement')->change();;
        });

        Schema::table('restaurant_table_customer', function ($table) {
        });

        Schema::table('app_config', function ($table) {
            $table->string('badorder_settlements')->change();;
            $table->string('settlements_arrangements')->change();;
        });

        Schema::table('restaurant_bill', function (Blueprint $table) {
            $table->string('reason_cancelled')->change();;
        });

        Schema::table('restaurant_accepted_order_cancellation', function (Blueprint $table) {
            $table->string('reason_cancelled')->change();;
        });

        Schema::table('restaurant_bill', function (Blueprint $table) {
            $table->string('invoice_number')->change();;
        });

        Schema::table('user', function (Blueprint $table) {
        });

        Schema::table('restaurant_meal_type', function (Blueprint $table) {
            $table->string('type')->change();;
            $table->time('schedule')->change();;
        });

        Schema::table('restaurant_bill', function (Blueprint $table) {
            $table->string('meal_type')->change();;
        });


    }
}
