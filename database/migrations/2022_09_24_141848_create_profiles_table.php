<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->date('dob');
            $table->float('height',5,2);
            $table->float('weight',5,2);
            $table->float('footware_meansure',3,1);
            $table->string('photo',80)->nullable();
            $table->enum('',['Masculino'=>'Masculino','Femenino'=>'Femenino','NA'=>'NA']);
            $table->text('about_me')->nullable();
            $table->string('favorite_brands',80)->nullable();
            $table->string('favorite_colors',80)->nullable();
            $table->string('favorite_sports',80)->nullable();
            $table->enum('gym_attendance',['Si'=>'Si','No'=>'No'])->default('No');
            $table->enum('take_supplements',['Si'=>'Si','No'=>'No'])->default('No');

            $table->integer('views')->default(0);
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
        Schema::dropIfExists('profiles');
    }
}
