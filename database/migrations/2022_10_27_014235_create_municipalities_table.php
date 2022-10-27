<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMunicipalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states');

            $table->string('short_name',20)->nullable();
            $table->string('name',120);
            $table->integer('is_deleted')->default(0);
            $table->enum('active',['Si'=>'Si','No'=>'No'])->default('No');

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
        Schema::dropIfExists('municipalities');
    }
}
