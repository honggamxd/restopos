<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_bill', function (Blueprint $table) {
            //
            $table->string('reason_cancelled')->after('table_name');
        });

        Schema::table('restaurant_accepted_order_cancellation', function (Blueprint $table) {
            //
            $table->string('reason_cancelled')->after('restaurant_order_cancellation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_bill', function (Blueprint $table) {
            //
            $table->dropColumn('reason_cancelled')->restaurant_order_cancellation_id;
        });

        Schema::table('restaurant_accepted_order_cancellation', function (Blueprint $table) {
            //
            $table->dropColumn('reason_cancelled');
        });
    }
}
