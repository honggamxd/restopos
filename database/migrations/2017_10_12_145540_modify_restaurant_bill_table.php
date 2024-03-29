<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRestaurantBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('restaurant_bill', function ($table) {
            $table->integer('sc_pwd')->after('gross_billing');
            $table->renameColumn('cashier', 'cashier_id');
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
        Schema::table('restaurant_bill', function ($table) {
            $table->dropColumn('sc_pwd');
            $table->renameColumn('cashier_id', 'cashier');
        });
    }
}
