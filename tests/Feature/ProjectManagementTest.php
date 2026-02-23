<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_project_with_single_client(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Website Redesign',
                'description' => 'Complete redesign of company website',
                'clients' => [$client->id],
                'manager_id' => $manager->id,
                'budget' => 50000,
                'currency' => 'USD',
                'priority' => 8,
                'start_date' => '2026-03-01',
                'end_date' => '2026-06-30',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'code', 'name'],
                'message',
            ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Website Redesign',
            'manager_id' => $manager->id,
        ]);
    }

    /** @test */
    public function admin_can_create_project_with_multiple_clients(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $clients = Client::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Mobile App',
                'clients' => $clients->pluck('id')->toArray(),
                'manager_id' => $manager->id,
                'priority' => 9,
                'start_date' => '2026-04-01',
            ]);

        $response->assertStatus(201);

        $project = Project::where('name', 'Mobile App')->first();
        $this->assertCount(3, $project->clients);
    }

    /** @test */
    public function project_defaults_to_tarqumi_client_if_none_specified(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $defaultClient = Client::factory()->create(['is_default' => true]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Internal Tool',
                'manager_id' => $manager->id,
                'priority' => 5,
                'start_date' => '2026-03-15',
            ]);

        $response->assertStatus(201);

        $project = Project::where('name', 'Internal Tool')->first();
        $this->assertTrue($project->clients->contains($defaultClient));
    }

    /** @test */
    public function project_code_auto_generated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Project',
                'clients' => [$client->id],
                'manager_id' => $manager->id,
                'priority' => 5,
                'start_date' => '2026-03-01',
            ]);

        $response->assertStatus(201);

        $project = Project::where('name', 'Test Project')->first();
        $this->assertMatchesRegularExpression('/^PROJ-\d{4}-\d{4}$/', $project->code);
    }

    /** @test */
    public function first_client_is_marked_as_primary(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $clients = Client::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Project',
                'clients' => $clients->pluck('id')->toArray(),
                'manager_id' => $manager->id,
                'priority' => 5,
                'start_date' => '2026-03-01',
            ]);

        $response->assertStatus(201);

        $project = Project::where('name', 'Test Project')->first();
        $primaryClient = $project->clients()->wherePivot('is_primary', true)->first();
        $this->assertEquals($clients->first()->id, $primaryClient->id);
    }

    /** @test */
    public function priority_must_be_between_1_and_10(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Project',
                'clients' => [$client->id],
                'manager_id' => $manager->id,
                'priority' => 15,
                'start_date' => '2026-03-01',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['priority']);
    }

    /** @test */
    public function end_date_must_be_after_start_date(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Project',
                'clients' => [$client->id],
                'manager_id' => $manager->id,
                'priority' => 5,
                'start_date' => '2026-06-01',
                'end_date' => '2026-03-01',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['end_date']);
    }

    /** @test */
    public function maximum_10_clients_allowed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $clients = Client::factory()->count(11)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Project',
                'clients' => $clients->pluck('id')->toArray(),
                'manager_id' => $manager->id,
                'priority' => 5,
                'start_date' => '2026-03-01',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['clients']);
    }

    /** @test */
    public function at_least_one_client_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Project',
                'clients' => [],
                'manager_id' => $manager->id,
                'priority' => 5,
                'start_date' => '2026-03-01',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['clients']);
    }

    /** @test */
    public function employee_cannot_create_project(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $client = Client::factory()->create();

        $response = $this->actingAs($employee, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Project',
                'clients' => [$client->id],
                'manager_id' => $employee->id,
                'priority' => 5,
                'start_date' => '2026-03-01',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_list_projects(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        Project::factory()->count(10)->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'code', 'name'],
                ],
                'meta',
            ]);
    }

    /** @test */
    public function search_filters_projects(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        Project::factory()->create(['name' => 'Website Redesign', 'manager_id' => $manager->id]);
        Project::factory()->create(['name' => 'Mobile App', 'manager_id' => $manager->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/projects?search=website');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function status_filter_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        Project::factory()->count(3)->create(['status' => 'planning', 'manager_id' => $manager->id]);
        Project::factory()->count(2)->create(['status' => 'implementation', 'manager_id' => $manager->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/projects?status=planning');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function priority_filter_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        Project::factory()->count(3)->create(['priority' => 8, 'manager_id' => $manager->id]);
        Project::factory()->count(2)->create(['priority' => 3, 'manager_id' => $manager->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/projects?priority_min=7');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function manager_filter_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager1 = User::factory()->create(['role' => 'admin']);
        $manager2 = User::factory()->create(['role' => 'admin']);
        
        Project::factory()->count(3)->create(['manager_id' => $manager1->id]);
        Project::factory()->count(2)->create(['manager_id' => $manager2->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/v1/projects?manager_id={$manager1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function admin_can_update_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/projects/{$project->id}", [
                'status' => 'implementation',
                'priority' => 10,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => 'implementation',
            'priority' => 10,
        ]);
    }

    /** @test */
    public function admin_can_update_project_clients(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['manager_id' => $manager->id]);
        $newClients = Client::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/projects/{$project->id}", [
                'clients' => $newClients->pluck('id')->toArray(),
            ]);

        $response->assertStatus(200);

        $project->refresh();
        $this->assertCount(3, $project->clients);
    }

    /** @test */
    public function admin_can_delete_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/projects/{$project->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('projects', [
            'id' => $project->id,
        ]);
    }

    /** @test */
    public function deleted_project_can_be_restored(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['manager_id' => $manager->id]);
        $project->delete();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/projects/{$project->id}/restore");

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function kanban_data_groups_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        
        Project::factory()->count(2)->create(['status' => 'planning', 'manager_id' => $manager->id]);
        Project::factory()->count(3)->create(['status' => 'implementation', 'manager_id' => $manager->id]);
        Project::factory()->count(1)->create(['status' => 'testing', 'manager_id' => $manager->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/projects/kanban');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'planning',
                    'analysis',
                    'design',
                    'implementation',
                    'testing',
                    'deployment',
                ],
            ]);
    }

    /** @test */
    public function pagination_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        Project::factory()->count(30)->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/projects?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.per_page', 10);
    }

    /** @test */
    public function created_by_and_updated_by_tracked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/projects', [
                'name' => 'Test Project',
                'clients' => [$client->id],
                'manager_id' => $manager->id,
                'priority' => 5,
                'start_date' => '2026-03-01',
            ]);

        $response->assertStatus(201);

        $project = Project::where('name', 'Test Project')->first();
        $this->assertEquals($admin->id, $project->created_by);
        $this->assertEquals($admin->id, $project->updated_by);
    }
}
