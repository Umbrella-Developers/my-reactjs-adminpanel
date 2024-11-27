<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRolePermissionTest extends TestCase
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
    public function it_can_create_a_user_with_super_admin_role()
    {
        // Create the Super Admin role
        $role = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'sanctum',   
        ]);

        // Create a new user and assign the Super Admin role
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        // Assert the user has the Super Admin role
        $this->assertTrue($user->hasRole('Super Admin'));
    }
}
