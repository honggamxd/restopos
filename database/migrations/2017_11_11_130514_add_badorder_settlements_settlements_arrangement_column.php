<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBadorderSettlementsSettlementsArrangementColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('app_config', function ($table) {
            $table->string('badorder_settlements')->after('settlements');
            $table->string('settlements_arrangements')->after('badorder_settlements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('app_config', function ($table) {
            $table->dropColumn('badorder_settlements');
            $table->dropColumn('settlements_arrangements');
        });
    }
}
