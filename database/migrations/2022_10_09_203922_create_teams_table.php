<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',40);
            $table->string('logo',120);
            $table->string('photo',120);
            $table->text('description');
            $table->enum('branch',['Varonil'=>'Varonil', 'Femenil'=>'Femenil', 'Mixto'=>'Mixto']);

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')->references('id')->on('tournaments');

            $table->bigInteger('sport_id')->unsigned();
            $table->foreign('sport_id')->references('id')->on('sports');

            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            
            $table->boolean('is_deleted')->default(0);
            $table->bigInteger('clicks');
            $table->bigInteger('prints');



            
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
        Schema::dropIfExists('teams');
    }
}
