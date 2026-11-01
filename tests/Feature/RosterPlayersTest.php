<?php

namespace Tests\Feature;

use App\Roster;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RosterPlayersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->instance('env', 'testing');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('last_name');
            $table->integer('active')->default(1);
            $table->integer('is_deleted')->default(0);
        });
        Schema::create('rosters', function (Blueprint $table) {
            $table->increments('id');
            foreach (['user_id', 'team_id', 'tournament_id', 'category_id'] as $field) {
                $table->integer($field);
            }
            foreach (['name', 'last_name', 'number', 'verified'] as $field) {
                $table->string($field);
            }
            $table->string('photo')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamps();
        });
        require_once database_path('migrations/2026_10_15_000001_add_player_id_to_rosters_table.php');
        (new \AddPlayerIdToRostersTable)->up();
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Ana', 'last_name' => 'López', 'active' => 1, 'is_deleted' => 0],
            ['id' => 2, 'name' => 'Luis', 'last_name' => 'Pérez', 'active' => 1, 'is_deleted' => 0],
            ['id' => 3, 'name' => 'Inactivo', 'last_name' => 'Uno', 'active' => 0, 'is_deleted' => 0],
            ['id' => 4, 'name' => 'Borrado', 'last_name' => 'Dos', 'active' => 1, 'is_deleted' => 1],
        ]);
    }

    private function payload()
    {
        return ['player_id' => 2, 'user_id' => 4, 'is_deleted' => 1,
            'name' => 'Manipulado', 'last_name' => 'Manipulado', 'tournament_id' => 1,
            'category_id' => 1, 'team_id' => 1, 'number' => '10', 'verified' => 'NO'];
    }

    public function test_fetch_only_returns_active_non_deleted_users_and_public_identity_fields()
    {
        $this->getJson('/roster_players')->assertStatus(401);
        $this->postJson('/rosters', $this->payload())->assertStatus(401);
        $this->actingAs(User::find(1));
        $this->getJson('/roster_players')->assertExactJson([
            ['id' => 1, 'name' => 'Ana', 'last_name' => 'López'],
            ['id' => 2, 'name' => 'Luis', 'last_name' => 'Pérez'],
        ]);
    }

    public function test_one_player_can_have_multiple_rosters_and_creator_is_preserved()
    {
        $this->actingAs(User::find(1));
        $this->postJson('/rosters', $this->payload())->assertJson(['type' => 'success']);
        $this->postJson('/rosters', array_merge($this->payload(), ['team_id' => 2]))
            ->assertJson(['type' => 'success']);
        $row = Roster::first();
        $this->assertSame('Luis', $row->name);
        $this->assertSame('Pérez', $row->last_name);
        $this->assertEquals(1, $row->user_id);
        $this->assertEquals(0, $row->is_deleted);
        $this->assertEquals(2, $row->player->id);
        $this->assertEquals(1, $row->registeredBy->id);
        $this->assertSame(2, User::find(2)->rosters()->count());
        $this->assertSame(2, User::find(1)->registeredRosters()->count());
        $this->getJson('/rosters/'.$row->id)->assertJson(['player_id' => 2]);
        $this->actingAs(User::find(2));
        $this->postJson('/rosters', array_merge($this->payload(), ['id' => $row->id, 'player_id' => 1]))
            ->assertJson(['type' => 'success']);
        $this->assertSame('Ana', $row->fresh()->name);
        $this->assertEquals(1, $row->fresh()->user_id);
    }

    public function test_missing_invalid_inactive_or_deleted_player_is_rejected_on_create_and_update()
    {
        $this->actingAs(User::find(1));
        $this->postJson('/rosters', $this->payload())->assertJson(['type' => 'success']);
        foreach ([null, 'invalid', 999, 3, 4] as $playerId) {
            foreach ([null, Roster::first()->id] as $id) {
                $this->postJson('/rosters', array_merge($this->payload(), ['id' => $id, 'player_id' => $playerId]))
                    ->assertJson(['type' => 'error'])->assertJsonStructure(['errors' => ['player_id']]);
            }
        }
        $this->assertSame(1, Roster::count());
        $this->assertEquals(2, Roster::first()->player_id);
    }
}
