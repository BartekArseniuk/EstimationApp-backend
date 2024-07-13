<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
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

    public function test_can_list_users()
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->getJson('/api/users', $this->authorizationHeader);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Test User', 'email' => 'test@example.com', 'role' => 'user']);
    }

    public function test_can_update_user()
    {
        $user = User::create([
            'name' => 'Original User',
            'email' => 'original@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $updatedData = [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updatedData, $this->authorizationHeader);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', $updatedData);
    }

    public function test_can_delete_user()
    {
        $user = User::create([
            'name' => 'User to Delete',
            'email' => 'delete@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->deleteJson("/api/users/{$user->id}", [], $this->authorizationHeader);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}