<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuance', function (Blueprint $table) {
          $table->increments('id');
          $table->string('issuance_from');
          $table->string('issuance_number');
          $table->text('comments');
          $table->integer('date_');
          $table->integer('date_time');
          $table->integer('user_id')->unsigned();
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

        Schema::dropIfExists('issuance');
    }
}
