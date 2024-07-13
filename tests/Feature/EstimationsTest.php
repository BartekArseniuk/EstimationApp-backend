<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\Estimation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstimationsTest extends TestCase
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

    public function test_can_create_estimation()
    {
        $client = Client::create([
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ]);

        $project = Project::create([
            'name' => 'Test Project',
            'client_id' => $client->id,
        ]);

        $estimationData = [
            'name' => 'Test Estimation',
            'project_id' => $project->id,
            'client_id' => $client->id,
            'description' => 'Test Description',
            'date' => '2023-01-01',
            'type' => 'hourly',
            'amount' => 1000.00,
        ];

        $response = $this->postJson('api/estimations', $estimationData, $this->authorizationHeader);

        $response->assertStatus(201);
        $this->assertDatabaseHas('estimations', $estimationData);
    }

    public function test_can_get_estimations()
    {
        $client = Client::create([
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ]);

        $project = Project::create([
            'name' => 'Test Project',
            'client_id' => $client->id,
        ]);

        Estimation::create([
            'name' => 'Estimation 1',
            'project_id' => $project->id,
            'client_id' => $client->id,
            'description' => 'Description 1',
            'date' => '2023-01-01',
            'type' => 'hourly',
            'amount' => 500.00,
        ]);

        Estimation::create([
            'name' => 'Estimation 2',
            'project_id' => $project->id,
            'client_id' => $client->id,
            'description' => 'Description 2',
            'date' => '2023-01-02',
            'type' => 'fixed_price',
            'amount' => 1500.00,
        ]);

        $response = $this->getJson('api/estimations');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_can_update_estimation()
    {
        $client = Client::create([
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ]);

        $project = Project::create([
            'name' => 'Test Project',
            'client_id' => $client->id,
        ]);

        $estimation = Estimation::create([
            'name' => 'Original Estimation',
            'project_id' => $project->id,
            'client_id' => $client->id,
            'description' => 'Original Description',
            'date' => '2023-01-01',
            'type' => 'hourly',
            'amount' => 1000.00,
        ]);

        $updatedData = [
            'name' => 'Updated Estimation',
            'description' => 'Updated Description',
            'date' => '2023-02-01',
            'type' => 'fixed_price',
            'amount' => 2000.00,
        ];

        $response = $this->putJson('api/estimations/' . $estimation->id, $updatedData, $this->authorizationHeader);

        $response->assertStatus(200);
        $this->assertDatabaseHas('estimations', $updatedData);
    }

    public function test_can_delete_estimation()
    {
        $client = Client::create([
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ]);

        $project = Project::create([
            'name' => 'Test Project',
            'client_id' => $client->id,
        ]);

        $estimation = Estimation::create([
            'name' => 'Estimation to Delete',
            'project_id' => $project->id,
            'client_id' => $client->id,
            'description' => 'Description',
            'date' => '2023-01-01',
            'type' => 'hourly',
            'amount' => 1000.00,
        ]);

        $response = $this->deleteJson('api/estimations/' . $estimation->id, [], $this->authorizationHeader);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('estimations', ['id' => $estimation->id]);
    }
}