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
        $table->double('price',19,2);
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
        Schema::dropIfExists('restaurant_menu');
    }
}
