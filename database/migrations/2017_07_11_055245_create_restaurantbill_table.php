<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantbillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_bill', function (Blueprint $table) {
          $table->increments('id');
          $table->double('excess',19,2);
          $table->double('discounts',19,2);
          $table->double('gross_billing',19,2);
          $table->double('sc_pwd_discount',19,2);
          $table->double('sc_pwd_vat_exemption',19,2);
          $table->double('total_discount',19,2);
          $table->double('net_billing',19,2);
          $table->double('sales_net_of_vat_and_service_charge',19,2);
          $table->double('service_charge',19,2);
          $table->double('vatable_sales',19,2);
          $table->double('output_vat',19,2);
          $table->double('sales_inclusive_of_vat',19,2);
          $table->double('total_item_amount',19,2);
          $table->integer('check_number');
          $table->boolean('is_paid');
          $table->integer('date_');
          $table->integer('date_time');
          $table->integer('pax');
          $table->integer('server_id')->unsigned();
          $table->integer('cashier')->unsigned();
          $table->string('table_name');
          $table->integer('restaurant_id')->unsigned();
          $table->integer('restaurant_table_customer_id')->unsigned();
          $table->boolean('deleted');
          $table->integer('deleted_by')->unsigned();
          $table->text('deleted_comment');
          $table->integer('deleted_date');
          $table->text('guest_name');
          $table->text('room_number');
          $table->softDeletes();
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
         Schema::dropIfExists('restaurant_bill');
    }
}
