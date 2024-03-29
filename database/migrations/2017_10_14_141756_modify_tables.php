<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('restaurant_order', function ($table) {
            $table->boolean('has_cancellation_request')->after('has_cancelled');
        });
        Schema::table('restaurant_bill', function ($table) {
            $table->string('type')->after('restaurant_table_customer_id');
        });
        Schema::table('restaurant_order_cancellation', function ($table) {
            $table->boolean('finised_transaction')->after('approved');
            $table->string('type')->after('finised_transaction');
            $table->renameColumn('resturant_order_id','restaurant_order_id');
        });
        Schema::table('restaurant_accepted_order_cancellation', function ($table) {
            $table->string('settlement')->after('price');
            $table->boolean('has_settled')->after('settlement');
            $table->boolean('restaurant_order_cancellation_id')->after('has_settled');
            $table->integer('restaurant_bill_id')->after('restaurant_table_customer_id')->unsigned();
        });

        Schema::table('restaurant_table_customer', function ($table) {
            $table->boolean('has_cancellation_request')->after('has_order_cancelled');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('restaurant_order', function ($table) {
            $table->dropColumn('has_cancellation_request');
        });
        Schema::table('restaurant_bill', function ($table) {
            $table->dropColumn('type');
        });

        Schema::table('restaurant_order_cancellation', function ($table) {
            $table->dropColumn('finised_transaction');
            $table->dropColumn('type');
            $table->renameColumn('restaurant_order_id','resturant_order_id');
        });
        Schema::table('restaurant_accepted_order_cancellation', function ($table) {
            $table->dropColumn('settlement');
            $table->dropColumn('has_settled');
            $table->dropColumn('restaurant_order_cancellation_id');
            $table->dropColumn('restaurant_bill_id');
        });
        Schema::table('restaurant_table_customer', function ($table) {
            $table->dropColumn('has_cancellation_request');
            $table->dropColumn('deleted_at');
        });
    }
}
