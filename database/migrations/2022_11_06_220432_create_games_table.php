<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->bigincrements('id');

            $table->date('game_day');
            $table->time('game_time');
            $table->text('notes')->nullable();

            $table->enum('status',[
                'Abierto'=>'Abierto',
                'Cerrado'=>'Cerrado', 
                'Cancelado'=>'Cancelado',
                'Suspendido'=>'Suspendido',
                'Reagendado'=>'Reagendado',
                'Protestado'=>'Protestado',
            ])->default('Abierto');

            $table->enum('type',[
                'Amistoso'=>'Amistoso',
                'Pretemporada'=>'Pretemporada', 
                'Temporada Regular'=>'Temporada Regular',
                'Finales'=>'Finales',
                'Final'=>'Final',
                'Semifinal'=>'Semifinal',
                'Otro'=>'Otro',
            ])->default('Temporada Regular');

            $table->enum('branch',['Varonil'=>'Varonil', 'Femenil'=>'Femenil', 'Mixto'=>'Mixto']);

            $table->Integer('score_team1')->default(0);
            $table->Integer('score_team2')->default(0);

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')->references('id')->on('tournaments');

            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->bigInteger('journey_id')->unsigned();
            $table->foreign('journey_id')->references('id')->on('journeys');

            $table->bigInteger('team1_id')->unsigned();
            $table->foreign('team1_id')->references('id')->on('teams');

            $table->bigInteger('team2_id')->unsigned();
            $table->foreign('team2_id')->references('id')->on('teams');

            $table->integer('is_deleted')->default(0);

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
        Schema::dropIfExists('games');
    }
}
