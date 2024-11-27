<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_role()
    {
        $this->withoutMiddleware();
        $data = [
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ];

        $response = $this->post(route('role.store'), $data);
        $response->assertStatus(200); // Assuming you return 201 Created

        $this->assertDatabaseHas('roles', $data);
    }

    /** @test */
    public function it_can_read_a_role()
    {
        $this->withoutMiddleware();
        $role = Role::create(['name' => 'editor', 'guard_name' => 'sanctum']);

        $response = $this->get(route('role.show', ['role' => $role->id]));
        $response->assertStatus(200);

        // $response->assertJson([
        //     'name' => 'editor',
        //     'guard_name' => 'sanctum',
        // ]);
    }

    /** @test */
    public function it_can_update_a_role()
    {
        $this->withoutMiddleware();
        $role = Role::create(['name' => 'new editor', 'guard_name' => 'sanctum']);

        $updatedData = [
            'name' => 'moderator',
            'guard_name' => 'sanctum',
        ];

        $response = $this->put(route('role.update', ['role' => $role->id]), $updatedData);
        $response->assertStatus(200);

        $this->assertDatabaseHas('roles', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_role()
    {
        $this->withoutMiddleware();
        $role = Role::create(['name' => 'another editor', 'guard_name' => 'sanctum']);

        $response = $this->delete(route('role.destroy', ['id' => $role->id]));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
