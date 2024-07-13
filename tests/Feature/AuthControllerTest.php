<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private $authorizationHeader;

    protected function setUp(): void
    {
        parent::setUp();
        //change the admin token to the current one
        $this->authorizationHeader = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTcyMDg3NTI3MSwiZXhwIjoxNzIwODc4ODcxLCJuYmYiOjE3MjA4NzUyNzEsImp0aSI6InFwRGdtTFNtejRoSUNucFkiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.7pdr_s9clvGbgwa7H5NsVHLS-W0hF4AYWjNN_6BalKY',
        ];
    }

    public function test_can_register_user()
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'newpassword',
        ];

        $response = $this->postJson('/api/register', $userData, $this->authorizationHeader);

        $response->assertStatus(201)
            ->assertJsonStructure(['user' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_can_login_user()
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(200)
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);
    }
}