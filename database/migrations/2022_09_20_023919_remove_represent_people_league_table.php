<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRepresentPeopleLeagueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('leagues', function($table) {
            $table->dropColumn(['president', 'secretary','treasurer','referee_main']);
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
        Schema::table('leagues', function($table) {
            $table->string('president',80);
            $table->string('secretary',80);
            $table->string('treasurer',80);
            $table->string('referee_main',80);
        });
    }
}
