<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTablePageCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_category', function (Blueprint $table) {
            //
            $table->bigInteger('views')->unsigned();
            $table->bigInteger('prints')->unsigned();
            $table->bigInteger('likes')->unsigned();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_category', function (Blueprint $table) {
            //
            $table->dropColumn(['views', 'prints', 'likes']);
        });
    }
}
