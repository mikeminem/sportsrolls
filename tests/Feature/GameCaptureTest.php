<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GameCaptureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->instance('env', 'testing');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        foreach (['games', 'rosters', 'tournaments', 'actions', 'gamelogs'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('is_deleted')->default(0);
                if ($name === 'games') {
                    foreach (['team1_id', 'team2_id', 'tournament_id', 'score_team1', 'score_team2'] as $field) {
                        $table->integer($field)->default(0);
                    }
                    $table->string('status');
                    $table->string('access_code');
                } elseif ($name === 'rosters') {
                    $table->integer('team_id');
                } elseif ($name === 'tournaments') {
                    $table->integer('sport_id');
                } elseif ($name === 'actions') {
                    $table->integer('sport_id');
                    $table->integer('value');
                    foreach (['scoring', 'is_enabled', 'need_confirmation'] as $field) {
                        $table->string($field);
                    }
                } else {
                    foreach (['game_id', 'team_id', 'player_id', 'tournament_id', 'action_id', 'value'] as $field) {
                        $table->integer($field);
                    }
                    $table->integer('period')->nullable();
                    $table->string('time');
                    $table->string('status');
                    $table->string('comment')->nullable();
                }
            });
        }
        DB::table('games')->insert(['id' => 1, 'team1_id' => 10, 'team2_id' => 20,
            'tournament_id' => 1, 'status' => 'Programado', 'access_code' => 'secret']);
        DB::table('rosters')->insert(['id' => 1, 'team_id' => 10]);
        DB::table('tournaments')->insert(['id' => 1, 'sport_id' => 1]);
        DB::table('actions')->insert(['id' => 1, 'sport_id' => 1, 'value' => 2,
            'scoring' => 'Si', 'is_enabled' => 'Si', 'need_confirmation' => 'Si']);
    }

    private function payload()
    {
        return ['game_id' => 1, 'tournament_id' => 1, 'team_id' => 10,
            'player_id' => 1, 'action_id' => 1, 'status' => 'Exitoso',
            'time' => '00:00', 'value' => 999, 'is_deleted' => 1];
    }

    private function authorizeCapture()
    {
        $this->postJson('/game_data_entry', ['game_id' => 1, 'access_code' => 'secret'])
            ->assertJson(['access' => 'true']);
    }

    public function test_access_is_required_and_wrong_code_does_not_grant_it()
    {
        $this->postJson('/game_data_entry', ['game_id' => 2, 'access_code' => 'secret'])
            ->assertJson(['access' => 'false']);
        $this->postJson('/game_log', $this->payload())->assertStatus(403);
        $this->assertSame(0, DB::table('gamelogs')->count());
    }

    public function test_catalog_value_is_used_and_non_scoring_actions_do_not_add_points()
    {
        $this->authorizeCapture();
        $this->postJson('/game_log', $this->payload())->assertJson(['score' => 2]);
        $this->assertEquals(2, DB::table('gamelogs')->first()->value);
        $this->assertEquals(0, DB::table('gamelogs')->first()->is_deleted);
        DB::table('actions')->update(['scoring' => 'No']);
        $this->postJson('/game_log', $this->payload())->assertJson(['score' => 2]);
        DB::table('actions')->update(['scoring' => 'Si']);
        $this->postJson('/game_log', array_merge($this->payload(), ['status' => 'Fallado']))
            ->assertJson(['score' => 2]);
    }

    public function test_invalid_participants_actions_and_closed_games_are_rejected()
    {
        $this->authorizeCapture();
        foreach (['team_id' => 30, 'player_id' => 9, 'tournament_id' => 9, 'period' => -1, 'time' => '12:99'] as $key => $value) {
            $this->postJson('/game_log', array_merge($this->payload(), [$key => $value]))->assertStatus(422);
        }
        foreach (['is_enabled' => 'No', 'sport_id' => 9, 'is_deleted' => 1] as $key => $value) {
            $old = DB::table('actions')->value($key);
            DB::table('actions')->update([$key => $value]);
            $this->postJson('/game_log', $this->payload())->assertStatus(422);
            DB::table('actions')->update([$key => $old]);
        }
        DB::table('games')->update(['status' => 'Finalizado']);
        $this->postJson('/game_log', $this->payload())->assertStatus(409);
        $this->assertSame(0, DB::table('gamelogs')->count());
        $this->assertEquals(0, DB::table('games')->value('score_team1'));
    }

    public function test_score_failure_rolls_back_the_event()
    {
        $this->authorizeCapture();
        DB::unprepared("CREATE TRIGGER reject_score BEFORE UPDATE ON games BEGIN SELECT RAISE(ABORT, 'forced failure'); END");
        $this->postJson('/game_log', $this->payload())->assertStatus(500);
        $this->assertSame(0, DB::table('gamelogs')->count());
        $this->assertEquals(0, DB::table('games')->value('score_team1'));
    }
}
