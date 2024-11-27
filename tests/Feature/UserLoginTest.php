<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserLoginTest extends TestCase
{
    // use RefreshDatabase;

     /** @test */
     public function it_allows_users_to_login_with_correct_credentials()
     {
         $user = User::factory()->create();
 
         $credentials = [
             'email' => 'test@example.com',
             'password' => 'password',
         ];
 
         $response = $this->post(route('auth.login'), $credentials);
         $response->assertRedirect('/home'); // Assuming successful login redirects to '/home'
 
         $this->assertAuthenticatedAs($user);
     }
 
     /** @test */
     public function it_does_not_allow_users_to_login_with_incorrect_credentials()
     {
         $user = User::factory()->create([
             'email' => 'test@example.com',
             'password' => bcrypt('password'),
         ]);
 
         $credentials = [
             'email' => 'test@example.com',
             'password' => 'wrongpassword', // Incorrect password
         ];
 
         $response = $this->post(route('auth.login'), $credentials);
         $response->assertSessionHasErrors();
 
         $this->assertGuest();
     }
}
