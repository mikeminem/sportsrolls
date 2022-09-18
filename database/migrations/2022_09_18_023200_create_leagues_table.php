<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->increments('id');
            
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('name',80);
            $table->string('slogan',120)->nullable();
            $table->string('footer1',120)->nullable();
            $table->string('footer2',120)->nullable();
            $table->string('footer3',120)->nullable();
            $table->string('logo1',120)->nullable();
            $table->string('logo2',120)->nullable();
            $table->text('summary');
            $table->string('president',80);
            $table->string('secretary',80);
            $table->string('treasurer',80);
            $table->string('referee_main',80);
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
        Schema::dropIfExists('leagues');
    }
}
