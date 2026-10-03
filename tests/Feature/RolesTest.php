<?php

namespace Tests\Feature;

use App\Roles;
use App\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RolesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->instance('env', 'testing');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        require_once database_path('migrations/2026_10_03_000000_create_roles_table.php');
        (new \CreateRolesTable)->up();
    }

    public function test_guests_cannot_access_roles()
    {
        $this->get('/roles')->assertRedirect('/login');
        $this->postJson('/roles', ['name' => 'Guest'])->assertStatus(401);
        $this->getJson('/roles_list')->assertStatus(401);
    }

    public function test_roles_can_be_created_updated_and_soft_deleted()
    {
        $this->actingAs(new User(['id' => 1, 'role' => 'Admin']));
        $this->postJson('/roles', ['name' => 'Referee', 'is_deleted' => 1])
            ->assertJson(['type' => 'success']);
        $role = Roles::firstOrFail();
        $this->assertSame(0, $role->is_deleted);
        $this->assertSame(0, $role->is_default);
        $this->assertSame('', $role->description);

        $this->postJson('/roles', [
            'id' => $role->id, 'name' => 'Coach', 'description' => str_repeat('a', 500), 'is_default' => 1,
        ])->assertJson(['type' => 'success']);
        $this->getJson('/roles/'.$role->id)->assertJson(['name' => 'Coach', 'is_default' => 1]);
        $this->getJson('/roles', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertJson(['recordsTotal' => 1, 'data' => [['name' => 'Coach']]]);
        $this->getJson('/roles_list')->assertJson([['name' => 'Coach']]);
        $this->deleteJson('/roles/'.$role->id)->assertJson(['type' => 'success']);
        $this->assertSame(1, $role->fresh()->is_deleted);
        $this->getJson('/roles_list')->assertExactJson([]);
        $this->getJson('/roles/'.$role->id)->assertStatus(404);
        $this->postJson('/roles', ['id' => $role->id, 'name' => 'Deleted'])->assertStatus(404);
    }

    public function test_invalid_fields_are_rejected()
    {
        $this->actingAs(new User(['role' => 'Admin']));
        foreach ([['name' => ''], ['name' => str_repeat('a', 21)],
            ['name' => 'Coach', 'description' => str_repeat('a', 501)],
            ['name' => 'Coach', 'is_default' => 2], ['id' => -1, 'name' => 'Coach']] as $data) {
            $this->postJson('/roles', $data)->assertJson(['type' => 'error']);
        }
        $this->assertSame(0, Roles::count());
    }

    public function test_migration_can_be_reversed()
    {
        $this->assertSame(['id', 'name', 'description', 'is_deleted', 'is_default'], Schema::getColumnListing('roles'));
        (new \CreateRolesTable)->down();
        $this->assertFalse(Schema::hasTable('roles'));
    }
}
