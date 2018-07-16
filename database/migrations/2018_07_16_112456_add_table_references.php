<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('restaurant_bill', function (Blueprint $table) {
            $table->foreign('server_id')
            ->references('id')
            ->on('restaurant_server');

            $table->foreign('cashier_id')
            ->references('id')
            ->on('user');

            $table->foreign('restaurant_id')
            ->references('id')
            ->on('restaurant');

            $table->foreign('restaurant_table_customer_id')
            ->references('id')
            ->on('restaurant_table_customer');
        });
        
        Schema::table('restaurant_bill_detail', function (Blueprint $table) {
            $table->foreign('restaurant_menu_id')
            ->references('id')
            ->on('restaurant_menu');

            $table->foreign('restaurant_bill_id')
            ->references('id')
            ->on('restaurant_bill');

            $table->foreign('restaurant_id')
            ->references('id')
            ->on('restaurant');

        });
        Schema::table('restaurant_menu', function (Blueprint $table) {
            
            $table->foreign('restaurant_id')
            ->references('id')
            ->on('restaurant');

        });
        Schema::table('restaurant_temp_bill', function (Blueprint $table) {
            
            $table->foreign('restaurant_table_customer_id')
            ->references('id')
            ->on('restaurant_table_customer');

        });
        Schema::table('restaurant_temp_bill_detail', function (Blueprint $table) {
            
            $table->foreign('restaurant_menu_id')
            ->references('id')
            ->on('restaurant_menu');

            $table->foreign('restaurant_temp_bill_id')
            ->references('id')
            ->on('restaurant_temp_bill');

        });

        Schema::table('restaurant_payment', function (Blueprint $table) {

            $table->foreign('restaurant_bill_id')
            ->references('id')
            ->on('restaurant_bill');

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
            $table->dropForeign('restaurant_bill_server_id_foreign');
            $table->dropForeign('restaurant_bill_cashier_id_foreign');
            $table->dropForeign('restaurant_bill_restaurant_id_foreign');
            $table->dropForeign('restaurant_bill_restaurant_table_customer_id_foreign');
        });
        
        Schema::table('restaurant_bill_detail', function (Blueprint $table) {
            $table->dropForeign('restaurant_bill_detail_restaurant_menu_id_foreign');
            $table->dropForeign('restaurant_bill_detail_restaurant_bill_id_foreign');
            $table->dropForeign('restaurant_bill_detail_restaurant_id_foreign');
        });
        
        Schema::table('restaurant_menu', function (Blueprint $table) {
            $table->dropForeign('restaurant_menu_restaurant_id_foreign');
        });
        
        Schema::table('restaurant_temp_bill', function (Blueprint $table) {
            $table->dropForeign('restaurant_temp_bill_restaurant_table_customer_id_foreign');  
        });
        Schema::table('restaurant_temp_bill_detail', function (Blueprint $table) {
            $table->dropForeign('restaurant_temp_bill_detail_restaurant_menu_id');
            $table->dropForeign('restaurant_temp_bill_detail_restaurant_temp_bill_id');
        });
        
        Schema::table('restaurant_payment', function (Blueprint $table) {
            $table->dropForeign('restaurant_payment_restaurant_bill_id_foreign');
        });
    }
}
