<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPointsSystemToTournamentsTable extends Migration
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
            $table->enum("enable_points_system",['Si'=>'Si','No'=>'No'])->default('No');
            $table->integer('winner_points')->default(0);
            $table->integer('loser_points')->default(0);
            $table->integer('draw_points')->default(0);
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
            $table->dropColumn(['enable_points_system','winner_points', 'loser_points', 'draw_points']);

        });
    }
}
