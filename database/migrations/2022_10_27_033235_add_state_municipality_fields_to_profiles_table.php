<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateMunicipalityFieldsToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            //

            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states');

            $table->bigInteger('municipality_id')->unsigned();
            $table->foreign('municipality_id')->references('id')->on('municipalities');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            //
            $table->dropColumn(['state_id','municipality_id']);


        });
    }
}
