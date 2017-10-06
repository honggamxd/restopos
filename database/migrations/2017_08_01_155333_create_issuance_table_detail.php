<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuanceTableDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuance_detail', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('inventory_item_id')->unsigned();
          $table->double('quantity');
          $table->integer('date_');
          $table->boolean('deleted');
          $table->integer('issuance_id')->unsigned();
          $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('issuance_detail');
    }
}
