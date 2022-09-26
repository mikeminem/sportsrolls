<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnnecesaryFielsToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            //
            $table->dropColumn(['height','weight']);
            $table->string('tshirt_size',10)->nullable();
            $table->string('favorite_number',10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            //
            $table->float('height',5,2);
            $table->float('weight',5,2);
            $table->dropColumn(['tshirt_size','favorite_number']);

        });
    }
}
