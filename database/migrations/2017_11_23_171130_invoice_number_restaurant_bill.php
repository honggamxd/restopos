<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InvoiceNumberRestaurantBill extends Migration
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
            $table->string('invoice_number')->after('excess');
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
            $table->dropColumn('invoice_number');
        });
    }
}
