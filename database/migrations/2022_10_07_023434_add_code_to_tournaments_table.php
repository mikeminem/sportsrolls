<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeToTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            //
            $table->string('code',8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            //
            $table->dropColumn(['code']);

        });
    }
}
