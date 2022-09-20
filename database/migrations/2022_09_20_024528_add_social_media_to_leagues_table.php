<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialMediaToLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leagues', function (Blueprint $table) {
            //
            $table->string('facebook',80)->nullable();
            $table->string('twitter',80)->nullable();
            $table->string('instagram',80)->nullable();
            $table->string('website',80)->nullable();
            $table->string('watsapp',80)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leagues', function (Blueprint $table) {
            //
            $table->dropColumn(['facebook', 'twitter','instagram','website','watsapp']);

        });
    }
}
