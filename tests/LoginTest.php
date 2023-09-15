<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user login.
     *
     * @return void
     */
    public function testUserLogin()
    {
        User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);
        $loginData = [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ];
        $response = $this->post('/api/login', $loginData);
        $response->assertStatus(200)->assertJsonStructure([
            'status',
            'message',
            'user',
            'authorisation' => [
                'token',
                'type'
            ]
        ]);
        $this->assertAuthenticated();
    }
}
