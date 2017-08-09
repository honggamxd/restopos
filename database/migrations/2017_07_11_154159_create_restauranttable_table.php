<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestauranttableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_table', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->integer('restaurant_id')->unsigned();
          $table->boolean('occupied');
          $table->boolean('deleted');
          $table->integer('deleted_by')->unsigned();
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
        Schema::dropIfExists('restaurant_table');
    }
}
