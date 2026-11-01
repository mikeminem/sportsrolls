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
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
        });
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            foreach (['dob', 'photo', 'sex', 'favorite_number', 'about_me', 'tshirt_size'] as $field) {
                $table->string($field)->nullable();
            }
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
            [[], []],
            [['PLAYER'], []],
            [['CAPITAN'], []],
            [['promoter'], ['roster', 'tournaments', 'categories', 'teams', 'calendar', 'games']],
            [['admin'], ['tournaments', 'categories', 'teams', 'calendar', 'games', 'users', 'roles', 'catalogs']],
            [['player', 'capitan', 'promoter', 'admin'], ['roster', 'tournaments', 'categories', 'teams', 'calendar', 'games', 'users', 'roles', 'catalogs']],
        ];
    }

    /** @dataProvider roleCombinations */
    public function test_cards_follow_roles_without_duplicates($roles, $expected)
    {
        $this->signIn($roles);
        $response = $this->get('/dashboard')->assertStatus(200);
        preg_match_all('/data-dashboard-card="([^"]+)"/', $response->getContent(), $matches);
        $this->assertSame($expected, $matches[1]);
        $response->assertDontSee('Inscribir equipo')->assertSee('data-dashboard-profile');
        $this->assertSame(in_array('player', array_map('strtolower', $roles)),
            strpos($response->getContent(), 'data-player-statistics') !== false);
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

    public function test_header_uses_own_profile_and_escapes_personal_information()
    {
        $this->signIn(['player']);
        DB::table('users')->where('id', 1)->update(['last_name' => 'López',
            'email' => 'ana@example.test', 'phone' => '4921234567']);
        $this->actingAs(User::find(1));
        DB::table('profiles')->insert([
            ['user_id' => 1, 'dob' => '2000-03-01', 'photo' => 'ana.jpg', 'sex' => 'Femenino',
                'favorite_number' => '12', 'about_me' => '<script>unsafe()</script>', 'tshirt_size' => 'M'],
            ['user_id' => 2, 'dob' => null, 'photo' => 'other.jpg', 'sex' => null,
                'favorite_number' => null, 'about_me' => 'Perfil ajeno', 'tshirt_size' => null],
        ]);
        $this->get('/dashboard')->assertStatus(200)->assertSee('López')
            ->assertSee('ana@example.test')->assertSee('4921234567')->assertSee('01/03/2000')
            ->assertSee('photo/ana.jpg')->assertSee('Femenino')->assertSee('data-player-statistics')
            ->assertDontSee('<script>unsafe()</script>')->assertDontSee('Perfil ajeno')
            ->assertSee('Estadísticas próximamente');
    }

    public function test_header_handles_missing_profile_and_legacy_zero_birth_date()
    {
        $this->signIn(['admin']);
        $this->get('/dashboard')->assertStatus(200)->assertSee('Agrega tu fotografía')
            ->assertSee('Por completar')->assertDontSee('data-player-statistics');
        DB::table('profiles')->insert(['user_id' => 1, 'dob' => '0000-00-00']);
        $this->actingAs(User::find(1));
        $this->get('/dashboard')->assertStatus(200)->assertDontSee('30/11/-0001');
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
