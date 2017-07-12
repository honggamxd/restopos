<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_order', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('date_');
          $table->integer('date_time');
          $table->integer('que_number');
          $table->integer('pax');
          $table->string('table_name');
          $table->integer('restaurant_id');
          $table->integer('server');
          $table->boolean('deleted');
          $table->text('deleted_comment');
          $table->integer('deleted_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_order');
    }
}
