<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientsTest extends TestCase
{
    use RefreshDatabase;

    private $authorizationHeader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authorizationHeader = [
            //change the admin token to the current one
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTcyMDg3NTI3MSwiZXhwIjoxNzIwODc4ODcxLCJuYmYiOjE3MjA4NzUyNzEsImp0aSI6InFwRGdtTFNtejRoSUNucFkiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.7pdr_s9clvGbgwa7H5NsVHLS-W0hF4AYWjNN_6BalKY',
        ];
    }

    public function test_can_create_client()
    {
        $clientData = [
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ];

        $response = $this->postJson('api/clients', $clientData, $this->authorizationHeader);

        $response->assertStatus(201);
        $this->assertDatabaseHas('clients', $clientData);
    }

    public function test_can_get_clients()
    {
        Client::create([
            'name' => 'Client 1',
            'country' => 'Country 1',
            'email' => 'client1@example.com',
        ]);

        Client::create([
            'name' => 'Client 2',
            'country' => 'Country 2',
            'email' => 'client2@example.com',
        ]);

        Client::create([
            'name' => 'Client 3',
            'country' => 'Country 3',
            'email' => 'client3@example.com',
        ]);

        $response = $this->getJson('api/clients');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_update_client()
    {
        $client = Client::create([
            'name' => 'Original Client',
            'country' => 'Original Country',
            'email' => 'original@example.com',
        ]);

        $updatedData = [
            'name' => 'Updated Client',
            'country' => 'Updated Country',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson('api/clients/' . $client->id, $updatedData, $this->authorizationHeader);

        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', $updatedData);
    }

    public function test_can_delete_client()
    {
        $client = Client::create([
            'name' => 'Client to Delete',
            'country' => 'Country to Delete',
            'email' => 'delete@example.com',
        ]);

        $response = $this->deleteJson('api/clients/' . $client->id, [], $this->authorizationHeader);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}