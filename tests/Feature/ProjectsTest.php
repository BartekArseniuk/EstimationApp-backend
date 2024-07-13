<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectsTest extends TestCase
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

    public function test_can_create_project()
    {
        $client = Client::create([
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ]);

        $projectData = [
            'name' => 'Test Project',
            'client_id' => $client->id,
        ];

        $response = $this->postJson('api/projects', $projectData, $this->authorizationHeader);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', $projectData);
    }

    public function test_can_get_projects()
    {
        $client = Client::create([
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ]);

        Project::create([
            'name' => 'Project 1',
            'client_id' => $client->id,
        ]);

        Project::create([
            'name' => 'Project 2',
            'client_id' => $client->id,
        ]);

        $response = $this->getJson('api/projects');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_can_update_project()
    {
        $client = Client::create([
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ]);

        $project = Project::create([
            'name' => 'Original Project',
            'client_id' => $client->id,
        ]);

        $updatedData = [
            'name' => 'Updated Project',
            'client_id' => $client->id,
        ];

        $response = $this->putJson('api/projects/' . $project->id, $updatedData, $this->authorizationHeader);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', $updatedData);
    }

    public function test_can_delete_project()
    {
        $client = Client::create([
            'name' => 'Test Client',
            'country' => 'Test Country',
            'email' => 'test@example.com',
        ]);

        $project = Project::create([
            'name' => 'Project to Delete',
            'client_id' => $client->id,
        ]);

        $response = $this->deleteJson('api/projects/' . $project->id, [], $this->authorizationHeader);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}