<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->string('description', 500)->default('');
            $table->integer('is_deleted')->default(0);
            $table->integer('is_default')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
