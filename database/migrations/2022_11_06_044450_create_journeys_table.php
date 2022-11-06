<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journeys', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->string('name',120);
            $table->text('notes')->nullable();

            $table->date('start_at');
            $table->date('finish_at');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')->references('id')->on('tournaments');

            $table->bigInteger('sport_id')->unsigned();
            $table->foreign('sport_id')->references('id')->on('sports');

            $table->integer('is_deleted')->default(0);
            $table->enum('status',['Abierto'=>'Abierto','Cerrado'=>'Cerrado', 'Cancelado'=>'Cancelado'])->default('Abierto');

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
        Schema::dropIfExists('journeys');
    }
}
