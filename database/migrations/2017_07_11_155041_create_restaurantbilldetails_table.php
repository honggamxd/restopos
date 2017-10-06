<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantbilldetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_bill_detail', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('restaurant_menu_id')->unsigned();
          $table->string('restaurant_menu_name');
          $table->text('special_instruction');
          $table->integer('quantity');
          $table->double('price',19,2);
          $table->integer('date_');
          $table->integer('restaurant_bill_id')->unsigned();
          $table->integer('restaurant_id')->unsigned();
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
        Schema::dropIfExists('restaurant_bill_detail');
    }
}
