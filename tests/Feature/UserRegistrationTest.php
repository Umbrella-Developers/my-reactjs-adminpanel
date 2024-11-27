<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRegistrationTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    // public function a_user_can_register()
    // {        
    //     Role::create([
    //         'name' => 'Manager',
    //         'guard_name' => 'sanctum'
    //     ]);
    //     $response = $this->post('auth/register', [
    //         'name' => 'John Doe',
    //         'email' => 'johndoe@example.com',
    //         'password' => 'password',
    //         'password_confirmation' => 'password',
    //         'phone_number' => '+1234567890',
    //     ]);
    //     // dd("asdf", $response);
    //     $response->assertRedirect('/home');
        
    //     // Log the user details from the database for debugging
    //     $user = User::where('email', 'johndoe@example.com')->first();
    //     if ($user) {
    //         echo "User ID: {$user->id}, Email: {$user->email}\n";
    //     } else {
    //         echo "User not found\n";
    //     }

    //     $this->assertDatabaseHas('users', [
    //         'email' => 'johndoe@example.com',
    //     ]);
    // }

}
