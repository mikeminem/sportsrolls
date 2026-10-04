<?php

namespace Tests\Feature;

use App\Roles;
use App\User;
use App\UserRoles;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserRolesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->instance('env', 'testing');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('is_deleted')->default(0);
        });
        DB::table('users')->insert([['id' => 1], ['id' => 2]]);
        require_once database_path('migrations/2026_10_03_000000_create_roles_table.php');
        require_once database_path('migrations/2026_10_03_000001_create_user_roles_table.php');
        (new \CreateRolesTable)->up();
        (new \CreateUserRolesTable)->up();
        foreach (['Player', 'Admin', 'Promoter'] as $name) {
            Roles::create(['name' => $name]);
        }
    }

    public function test_multiple_roles_update_and_removal()
    {
        $this->actingAs((new User(['role' => 'Player']))->setRelation('roles', Roles::where('name', 'Admin')->get()));
        foreach ([1, 2, 3] as $role) {
            $this->postJson('/user_roles', ['user_id' => 1, 'rol_id' => $role])->assertJson(['type' => 'success']);
        }
        $this->assertSame(3, User::find(1)->roles()->count());
        $this->getJson('/user_roles_list/1')->assertJsonCount(3);
        $this->getJson('/user_roles?user_id=2')->assertExactJson([]);
        $this->getJson('/user_roles?user_id=1', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertJson(['recordsTotal' => 3]);
        $link = UserRoles::first();
        $this->getJson('/user_roles/'.$link->id)->assertJson(['user_id' => 1, 'rol_id' => 1]);
        $this->postJson('/user_roles', ['id' => $link->id, 'user_id' => 1, 'rol_id' => 1])->assertJson(['type' => 'success']);
        $this->postJson('/user_roles', ['id' => $link->id, 'user_id' => 1, 'rol_id' => 2])->assertJson(['type' => 'error']);
        $this->postJson('/user_roles', ['id' => $link->id, 'user_id' => 2, 'rol_id' => 1])->assertJson(['type' => 'success']);
        $this->deleteJson('/user_roles/'.$link->id)->assertJson(['type' => 'success']);
        $this->assertNull(UserRoles::find($link->id));
        $this->assertSame(3, Roles::count());
        $this->assertSame(2, User::count());
        $this->getJson('/user_roles/'.$link->id)->assertStatus(404);
    }

    public function test_duplicate_missing_and_deleted_references_are_rejected()
    {
        $this->actingAs((new User(['role' => 'Player']))->setRelation('roles', Roles::where('name', 'Admin')->get()));
        $this->postJson('/user_roles', ['user_id' => 1, 'rol_id' => 1])->assertJson(['type' => 'success']);
        foreach ([['user_id' => 1, 'rol_id' => 1], ['user_id' => 99, 'rol_id' => 1],
            ['user_id' => 1, 'rol_id' => 99], ['user_id' => 1], ['user_id' => 'bad', 'rol_id' => 1]] as $data) {
            $this->postJson('/user_roles', $data)->assertJson(['type' => 'error']);
        }
        Roles::find(2)->update(['is_deleted' => 1]);
        $this->postJson('/user_roles', ['user_id' => 1, 'rol_id' => 2])->assertJson(['type' => 'error']);
        DB::table('users')->where('id', 2)->update(['is_deleted' => 1]);
        $this->postJson('/user_roles', ['user_id' => 2, 'rol_id' => 3])->assertJson(['type' => 'error']);
        Roles::find(1)->update(['is_deleted' => 1]);
        $this->getJson('/user_roles_list/1')->assertExactJson([]);
        $this->assertSame(1, UserRoles::count());
    }

    public function test_only_admins_can_manage_assignments()
    {
        $this->postJson('/user_roles', ['user_id' => 1, 'rol_id' => 2])->assertStatus(401);
        $this->actingAs((new User(['role' => 'Admin']))->setRelation('roles', collect()));
        $this->getJson('/user_roles')->assertStatus(403);
        $this->getJson('/user_roles_list/1')->assertStatus(403);
        $this->getJson('/user_roles/1')->assertStatus(403);
        $this->postJson('/user_roles', ['user_id' => 1, 'rol_id' => 2])->assertStatus(403);
        $this->deleteJson('/user_roles/1')->assertStatus(403);
    }

    public function test_role_membership_uses_assignments_and_ignores_deleted_roles()
    {
        $user = User::find(1);
        $user->role = 'Admin';
        $this->assertFalse($user->hasRole('Admin'));
        $user->roles()->sync([1, 3]);
        $user->unsetRelation('roles');
        $this->assertTrue($user->hasRole('Player'));
        $this->assertTrue($user->hasRole('pRoMoTeR'));
        $this->assertFalse($user->hasRole('Admin'));
        Roles::find(3)->update(['is_deleted' => 1]);
        $user->unsetRelation('roles');
        $this->assertFalse($user->hasRole('Promoter'));
    }

    public function test_menu_shows_sections_for_each_assigned_role()
    {
        $user = User::find(1);
        $user->roles()->sync([2, 3]);
        $this->actingAs($user);
        $server = $_SERVER;
        try {
            $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es';
            $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
            $menu = view('layouts.menu')->render();
            $this->assertContains(url('/users'), $menu);
            $this->assertContains(url('/stadiums'), $menu);
            $this->assertNotContains(url('/products'), $menu);
            $this->assertContains(url('league_info'), $menu);
            $this->assertContains('Admin, Promoter', $menu);

            $user->roles()->sync([1]);
            $user->unsetRelation('roles');
            $menu = view('layouts.menu')->render();
            $this->assertNotContains(url('/users'), $menu);
            $this->assertNotContains(url('/stadiums'), $menu);
            $this->assertNotContains(url('/products'), $menu);
            $this->assertNotContains(url('league_info'), $menu);

            $seller = Roles::create(['name' => 'Seller']);
            $user->roles()->sync([$seller->id]);
            $user->unsetRelation('roles');
            $menu = view('layouts.menu')->render();
            $this->assertContains(url('/products'), $menu);
            $this->assertNotContains(url('/users'), $menu);
        } finally {
            $_SERVER = $server;
        }
    }

    public function test_migration_can_be_reversed()
    {
        $this->assertSame(['id', 'user_id', 'rol_id'], Schema::getColumnListing('user_roles'));
        (new \CreateUserRolesTable)->down();
        $this->assertFalse(Schema::hasTable('user_roles'));
    }
}
