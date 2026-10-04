<?php

namespace Tests\Feature;

use App\Roles;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UsersMultiSelectTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->instance('env', 'testing');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            foreach (['name', 'last_name', 'email', 'phone', 'password', 'role'] as $column) {
                $table->string($column)->nullable();
            }
            $table->integer('active')->default(1);
            $table->integer('is_deleted')->default(0);
            $table->timestamps();
        });
        require_once database_path('migrations/2026_10_03_000000_create_roles_table.php');
        require_once database_path('migrations/2026_10_03_000001_create_user_roles_table.php');
        (new \CreateRolesTable)->up();
        (new \CreateUserRolesTable)->up();
        foreach (['Player', 'Admin', 'Promoter', 'Custom'] as $name) {
            Roles::create(['name' => $name]);
        }
        $this->actingAs((new User(['role' => 'Player']))->setRelation('roles', Roles::where('name', 'Admin')->get()));
    }

    private function payload(array $extra = [])
    {
        return array_merge(['name' => 'Ana', 'last_name' => 'Test', 'email' => 'ana@example.com',
            'phone' => '1234567890', 'active' => 1, 'password' => 'secret123', 'roles' => [1, 2]], $extra);
    }

    public function test_create_edit_and_list_multiple_roles()
    {
        $this->postJson('/users', $this->payload())->assertJson(['type' => 'success']);
        $user = User::first();
        $this->assertEquals([1, 2], $user->roles()->orderBy('roles.id')->pluck('roles.id')->all());
        $this->assertSame('Admin', $user->role);
        $this->getJson('/users/'.$user->id)->assertJson(['role_ids' => [1, 2]]);
        $this->getJson('/users', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertJsonFragment(['role' => 'Player, Admin']);
        $this->postJson('/users', $this->payload(['id' => $user->id, 'roles' => [3, 4], 'password' => '']))
            ->assertJson(['type' => 'success']);
        $user = $user->fresh();
        $this->assertEquals([3, 4], $user->roles()->orderBy('roles.id')->pluck('roles.id')->all());
        $this->assertSame('Promoter', $user->role);
        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->getJson('/users/'.$user->id)->assertJson(['role_ids' => [3, 4]]);
        $this->postJson('/users', $this->payload(['id' => $user->id, 'roles' => [4]]))
            ->assertJson(['type' => 'success']);
        $this->assertSame('Player', $user->fresh()->role);
        $this->assertSame(1, DB::table('user_roles')->count());
    }

    public function test_lowercase_catalog_preserves_admin_access()
    {
        Roles::find(2)->update(['name' => 'admin']);
        $this->postJson('/users', $this->payload())->assertJson(['type' => 'success']);
        $user = User::first();
        $this->assertSame('Admin', $user->role);
        $this->assertTrue($user->hasRole('ADMIN'));
        $this->actingAs($user);
        $this->getJson('/users/'.$user->id)->assertStatus(200);
    }

    public function test_registration_assigns_player_without_accepting_requested_admin_role()
    {
        Roles::find(1)->update(['name' => 'player', 'is_default' => 1]);
        $controller = new class extends \App\Http\Controllers\Auth\RegisterController {
            public function registerForTest(array $data) { return $this->create($data); }
        };
        $user = $controller->registerForTest($this->payload(['role' => 'Admin', 'roles' => [2]]));
        $this->assertTrue($user->hasRole('Player'));
        $this->assertFalse($user->hasRole('Admin'));
        $this->assertSame([1], $user->roles->pluck('id')->all());
    }

    public function test_registration_uses_configured_default_instead_of_player_name()
    {
        Roles::find(4)->update(['is_default' => 1]);
        $controller = new class extends \App\Http\Controllers\Auth\RegisterController {
            public function registerForTest(array $data) { return $this->create($data); }
        };
        $user = $controller->registerForTest($this->payload());
        $this->assertSame([4], $user->roles->pluck('id')->all());
        $this->assertTrue($user->hasRole('Custom'));
        $this->assertFalse($user->hasRole('Player'));
    }

    public function test_registration_requires_exactly_one_non_deleted_default()
    {
        $controller = new class extends \App\Http\Controllers\Auth\RegisterController {
            public function registerForTest(array $data) { return $this->create($data); }
        };
        foreach (['missing', 'deleted', 'multiple'] as $configuration) {
            if ($configuration === 'deleted') {
                Roles::find(1)->update(['is_default' => 1, 'is_deleted' => 1]);
            } elseif ($configuration === 'multiple') {
                Roles::find(1)->update(['is_deleted' => 0]);
                Roles::find(4)->update(['is_default' => 1]);
            }
            try {
                $controller->registerForTest($this->payload());
                $this->fail('Registration must reject an invalid default role configuration.');
            } catch (\Illuminate\Validation\ValidationException $exception) {
                $this->assertArrayHasKey('email', $exception->errors());
            }
            $this->assertSame(0, User::count());
            $this->assertSame(0, DB::table('user_roles')->count());
        }
    }

    public function test_assigned_admin_access_does_not_depend_on_legacy_role()
    {
        $user = User::create(['name' => 'Admin', 'role' => 'Player']);
        $user->roles()->attach(2);
        $this->actingAs($user);
        $this->getJson('/users/'.$user->id)->assertStatus(200);
        $this->getJson('/user_roles_list/'.$user->id)->assertStatus(200);

        Roles::find(2)->update(['is_deleted' => 1]);
        $user->unsetRelation('roles');
        $this->getJson('/users/'.$user->id)->assertStatus(403);
        $this->getJson('/user_roles_list/'.$user->id)->assertStatus(403);
    }

    public function test_stadium_ownership_and_admin_override_use_assigned_roles()
    {
        Schema::create('stadiums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('is_deleted')->default(0);
            $table->timestamps();
        });
        $user = User::create(['name' => 'Tester', 'role' => 'Admin']);
        $stadium = \App\Stadiums::create(['user_id' => $user->id + 1]);
        $this->actingAs($user);
        $controller = app(\App\Http\Controllers\StadiumsController::class);
        $request = new \Illuminate\Http\Request(['idDelete' => $stadium->id]);
        $this->assertSame('error', $controller->destroy($request)->getData()->type);
        $this->assertEquals(0, $stadium->fresh()->is_deleted);

        $user->role = 'Player';
        $user->roles()->attach(2);
        $user->unsetRelation('roles');
        $this->assertSame('success', $controller->destroy($request)->getData()->type);
        $this->assertEquals(1, $stadium->fresh()->is_deleted);

        $ownStadium = \App\Stadiums::create(['user_id' => $user->id]);
        $user->roles()->detach();
        $user->unsetRelation('roles');
        $request = new \Illuminate\Http\Request(['idDelete' => $ownStadium->id]);
        $this->assertSame('success', $controller->destroy($request)->getData()->type);
    }

    public function test_invalid_selection_does_not_modify_user_or_assignments()
    {
        $this->postJson('/users', $this->payload())->assertJson(['type' => 'success']);
        Roles::find(3)->update(['is_deleted' => 1]);
        foreach ([[], [999], [3], [1, 1], ['invalid'], 'Admin'] as $roles) {
            $this->postJson('/users', $this->payload(['id' => 1, 'name' => 'Changed', 'roles' => $roles]))
                ->assertJson(['type' => 'error']);
        }
        $this->assertSame('Ana', User::find(1)->name);
        $this->assertSame(2, DB::table('user_roles')->count());
        $this->postJson('/users', $this->payload(['id' => 999]))->assertJson(['type' => 'error']);
    }

    public function test_legacy_preselection_and_available_options()
    {
        $user = User::create(['name' => 'Legacy', 'role' => 'Promoter']);
        $this->getJson('/users/'.$user->id)->assertJson(['role_ids' => [3]]);
        Roles::find(4)->update(['is_deleted' => 1]);
        $server = $_SERVER;
        try {
            $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es';
            $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
            $this->get('/users')->assertStatus(200)->assertViewHas('roles', function ($roles) {
                return $roles->count() === 3 && !$roles->contains('name', 'Custom');
            })->assertSee('id="role" name="roles[]" multiple required')
                ->assertSee('vendor/multi-select/MultiSelect.js');
        } finally {
            $_SERVER = $server;
        }
    }

    public function test_non_admin_cannot_assign_roles_through_users()
    {
        $this->actingAs((new User(['role' => 'Admin']))->setRelation('roles', collect()));
        $this->postJson('/users', $this->payload())->assertStatus(403);
        $this->getJson('/users/1')->assertStatus(403);
        $this->getJson('/users')->assertStatus(403);
        $this->assertSame(0, User::count());
    }
}
