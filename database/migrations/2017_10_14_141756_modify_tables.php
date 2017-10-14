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
        Schema::table('restaurant_order_cancellation', function ($table) {
            $table->boolean('finised_transaction')->after('approved');
            $table->renameColumn('resturant_order_id','restaurant_order_id');
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

        Schema::table('restaurant_order_cancellation', function ($table) {
            $table->dropColumn('finised_transaction');
            $table->renameColumn('restaurant_order_id','resturant_order_id');
        });
    }
}
