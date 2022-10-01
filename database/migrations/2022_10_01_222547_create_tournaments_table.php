<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('title',120);
            $table->string('summary',240)->nullable();
            $table->string('contacts',240)->nullable();
            $table->string('header_image',120)->nullable();
            $table->text('convocatory')->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->enum('status',[
                'Por Empezar'=>'Por Empezar', 
                'En Progreso'=>'En Progreso',
                'Terminado'=>'Terminado', 
                'Inscripciones Abiertas'=>'Inscripciones Abiertas'])->default('Por Empezar');

            $table->date('start_day');
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
        Schema::dropIfExists('tournaments');
    }
}
