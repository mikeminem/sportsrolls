<?php

namespace Tests\Feature;

use App\Roles;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    private $originalServer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->instance('env', 'testing');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        $this->originalServer = $_SERVER;
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->integer('is_deleted')->default(0);
        });
        require_once database_path('migrations/2026_10_03_000000_create_roles_table.php');
        require_once database_path('migrations/2026_10_03_000001_create_user_roles_table.php');
        (new \CreateRolesTable)->up();
        (new \CreateUserRolesTable)->up();
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->originalServer;
        parent::tearDown();
    }

    private function signIn(array $names)
    {
        DB::table('users')->insert(['id' => 1, 'name' => 'Ana']);
        $user = User::find(1);
        foreach ($names as $name) {
            $role = Roles::create(['name' => $name]);
            $user->roles()->attach($role->id);
        }
        $this->actingAs($user);
        return $user;
    }

    public function roleCombinations()
    {
        return [
            [[], ['profile']],
            [['PLAYER'], ['profile']],
            [['CAPITAN'], ['profile']],
            [['promoter'], ['profile', 'roster', 'tournaments', 'categories', 'teams', 'calendar', 'games']],
            [['admin'], ['profile', 'tournaments', 'categories', 'teams', 'calendar', 'games', 'users', 'roles', 'catalogs']],
            [['player', 'capitan', 'promoter', 'admin'], ['profile', 'roster', 'tournaments', 'categories', 'teams', 'calendar', 'games', 'users', 'roles', 'catalogs']],
        ];
    }

    /** @dataProvider roleCombinations */
    public function test_cards_follow_roles_without_duplicates($roles, $expected)
    {
        $this->signIn($roles);
        $response = $this->get('/dashboard')->assertStatus(200);
        preg_match_all('/data-dashboard-card="([^"]+)"/', $response->getContent(), $matches);
        $this->assertSame($expected, $matches[1]);
        $response->assertDontSee('Inscribir equipo')->assertDontSee('Mis estadísticas');
    }

    public function test_dashboard_requires_login_and_home_redirects()
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/home')->assertRedirect('/login');
        $this->signIn(['player']);
        $this->get('/home')->assertRedirect('/dashboard');
        $this->get('/login')->assertRedirect('/dashboard');
    }

    public function test_calendar_only_contains_own_non_deleted_tournaments()
    {
        $this->signIn(['promoter']);
        DB::table('tournaments')->insert([
            ['user_id' => 1, 'title' => 'Mi torneo', 'slug' => 'mi-torneo', 'is_deleted' => 0],
            ['user_id' => 2, 'title' => 'Otro torneo', 'slug' => 'otro-torneo', 'is_deleted' => 0],
            ['user_id' => 1, 'title' => 'Borrado', 'slug' => 'borrado', 'is_deleted' => 1],
        ]);
        $this->get('/dashboard')->assertStatus(200)
            ->assertSee(url('/tournament_info/mi-torneo/calendar'))
            ->assertDontSee('otro-torneo/calendar')->assertDontSee('borrado/calendar');
    }

    public function test_empty_calendar_and_deleted_role()
    {
        $user = $this->signIn(['admin']);
        $this->get('/dashboard')->assertStatus(200)->assertSee('Aún no tienes torneos');
        Roles::where('name', 'admin')->update(['is_deleted' => 1]);
        $user->unsetRelation('roles');
        $this->get('/dashboard')->assertStatus(200)->assertDontSee('data-dashboard-card="users"');
        $this->getJson('/roles')->assertStatus(403);
    }

    public function test_player_cannot_access_role_management_directly()
    {
        $this->signIn(['player']);
        $this->getJson('/roles')->assertStatus(403);
        $this->postJson('/roles', ['name' => 'admin'])->assertStatus(403);
        $this->getJson('/roles/1')->assertStatus(403);
        $this->deleteJson('/roles/1')->assertStatus(403);
        $this->getJson('/roles_list')->assertStatus(403);
    }
}
