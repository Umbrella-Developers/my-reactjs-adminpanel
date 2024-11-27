<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRegistrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    //  /** @test */
    //  public function it_registers_a_new_user()
    //  {
    //      // Fake user data
    //      $userData = [
    //          'name' => 'Ali Baig',
    //          'email' => 'newone@allshorestaffing.com',
    //          'password' => 'password', // plain text password
    //          'c_password' => 'password',
    //          'phone_number' => '+923456693379',
    //      ];
 
         
    //      // Make a POST request to the register route
    //      $response = $this->post('auth/register', $userData);
    //     //  dd($userData, $response);
    //      // Assert that the response status is 201
    //      $response->assertStatus(201);
 
    //      // Assert that the user is in the database
    //      $this->assertDatabaseHas('users', [
    //          'email' => $userData['email'],
    //      ]);
 
    //      // Assert that the user data in the database is correct
    //      $user = User::where('email', $userData['email'])->first();
    //      $this->assertTrue(Hash::check('password', $user->password));
    //  }
 
    //  /** @test */
    //  public function it_requires_password_confirmation_to_register()
    //  {
    //      // Fake user data with missing password confirmation
    //      $userData = [
    //          'name' => 'Ali Baig',
    //          'email' => 'newone@allshorestaffing.com',
    //          'password' => 'password',
    //          'password_confirmation' => 'wrong-password',
    //          'phone_number' => '+923456693379',
    //      ];
 
    //      // Make a POST request to the register route
    //      $response = $this->post('/register', $userData);
 
    //      // Assert that the response status is 422 (Unprocessable Entity)
    //      $response->assertStatus(422);
    //  }
}
