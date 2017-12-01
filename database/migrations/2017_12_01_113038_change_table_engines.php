<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableEngines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $tables = [
                    'app_config',
                    'inventory_item',
                    'inventory_item_detail',
                    'issuance',
                    'issuance_detail',
                    'issuance_to',
                    'migrations',
                    'purchase',
                    'purchase_detail',
                    'restaurant',
                    'restaurant_accepted_order_cancellation',
                    'restaurant_bill',
                    'restaurant_bill_detail',
                    'restaurant_meal_type',
                    'restaurant_menu',
                    'restaurant_order',
                    'restaurant_order_cancellation',
                    'restaurant_order_cancellation_detail',
                    'restaurant_order_detail',
                    'restaurant_payment',
                    'restaurant_server',
                    'restaurant_table',
                    'restaurant_table_customer',
                    'restaurant_temp_bill',
                    'restaurant_temp_bill_detail',
                    'user'
                ];
                foreach ($tables as $table) {
                    DB::statement('ALTER TABLE ' . $table . ' ENGINE = InnoDB');
                }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = [
                            'app_config',
                            'inventory_item',
                            'inventory_item_detail',
                            'issuance',
                            'issuance_detail',
                            'issuance_to',
                            'migrations',
                            'purchase',
                            'purchase_detail',
                            'restaurant',
                            'restaurant_accepted_order_cancellation',
                            'restaurant_bill',
                            'restaurant_bill_detail',
                            'restaurant_meal_type',
                            'restaurant_menu',
                            'restaurant_order',
                            'restaurant_order_cancellation',
                            'restaurant_order_cancellation_detail',
                            'restaurant_order_detail',
                            'restaurant_payment',
                            'restaurant_server',
                            'restaurant_table',
                            'restaurant_table_customer',
                            'restaurant_temp_bill',
                            'restaurant_temp_bill_detail',
                            'user'
                        ];
                        foreach ($tables as $table) {
                            DB::statement('ALTER TABLE ' . $table . ' ENGINE = MyISAM');
                        }
    }
}
