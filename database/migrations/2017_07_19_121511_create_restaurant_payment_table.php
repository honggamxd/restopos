<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_payment', function (Blueprint $table) {
          $table->increments('id');
          $table->double('payment',19,2);
          $table->string('settlement');
          $table->integer('restaurant_id')->unsigned();
          $table->integer('date_');
          $table->integer('date_time');
          $table->integer('restaurant_bill_id')->unsigned();
          $table->boolean('bill_has_deleted');
          $table->boolean('deleted');
          $table->integer('deleted_by')->unsigned();
          $table->text('deleted_comment');
          $table->integer('deleted_date');
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
        Schema::dropIfExists('restaurant_payment');
    }
}
