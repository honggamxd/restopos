<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('restaurant_menu', function (Blueprint $table) {
        $table->increments('id');
        $table->string('category');
        $table->string('subcategory');
        $table->string('name');
        $table->boolean('is_prepared');
        $table->double('price');
        $table->integer('restaurant_id');
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
        Schema::dropIfExists('restaurant_menu');
    }
}
