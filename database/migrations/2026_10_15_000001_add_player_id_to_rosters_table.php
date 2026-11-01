<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlayerIdToRostersTable extends Migration
{
    public function up()
    {
        $mysql = DB::connection()->getDriverName() === 'mysql';
        $mode = null;
        if ($mysql) {
            $mode = DB::selectOne('SELECT @@SESSION.sql_mode AS mode')->mode;
            // Legacy rows contain zero dates. Preserve them during the table rebuild.
            $migrationMode = implode(',', array_diff(explode(',', $mode), ['NO_ZERO_DATE', 'NO_ZERO_IN_DATE']));
            DB::statement('SET SESSION sql_mode = ?', [$migrationMode]);
        }

        try {
            if (!Schema::hasColumn('rosters', 'player_id')) {
                Schema::table('rosters', function (Blueprint $table) {
                    // Existing rosters must be linked explicitly when they are edited.
                    $table->unsignedBigInteger('player_id')->nullable();
                });
            } elseif (DB::connection()->getDriverName() === 'mysql') {
                // Accommodate the manually added INT column while preserving its values.
                DB::statement('ALTER TABLE rosters MODIFY player_id BIGINT UNSIGNED NULL');
            }
    
            Schema::table('rosters', function (Blueprint $table) {
                $table->index('player_id');
                $table->foreign('player_id')->references('id')->on('users');
            });
        } finally {
            if ($mysql) {
                DB::statement('SET SESSION sql_mode = ?', [$mode]);
            }
        }
    }

    public function down()
    {
        Schema::table('rosters', function (Blueprint $table) {
            $table->dropForeign(['player_id']);
            $table->dropIndex(['player_id']);
            $table->dropColumn('player_id');
        });
    }
}
