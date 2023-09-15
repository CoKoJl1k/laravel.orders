<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;


class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration.
     *
     * @return void
     */
    public function testUserRegistration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->post('/api/register', $userData);
        $response->assertStatus(201)->assertJsonStructure([
            'status',
            'message',
            'user',
            'authorisation' => [
                'token',
                'type'
            ]
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }
}
