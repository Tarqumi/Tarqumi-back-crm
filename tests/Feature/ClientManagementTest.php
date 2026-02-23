<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_client(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/clients', [
                'name' => 'Acme Corp',
                'email' => 'contact@acme.com',
                'company_name' => 'Acme Corporation',
                'phone' => '+1234567890',
                'website' => 'acme.com',
                'industry' => 'Technology',
                'is_active' => true,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'email'],
                'message',
            ]);

        $this->assertDatabaseHas('clients', [
            'email' => 'contact@acme.com',
            'company_name' => 'Acme Corporation',
        ]);
    }

    /** @test */
    public function employee_cannot_create_client(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this->actingAs($employee, 'sanctum')
            ->postJson('/api/v1/clients', [
                'name' => 'Acme Corp',
                'email' => 'contact@acme.com',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function duplicate_email_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Client::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/clients', [
                'name' => 'Test Client',
                'email' => 'existing@example.com',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function website_url_auto_prefixed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/clients', [
                'name' => 'Test Client',
                'email' => 'test@client.com',
                'website' => 'testclient.com',
            ]);

        $response->assertStatus(201);
        
        $client = Client::where('email', 'test@client.com')->first();
        $this->assertEquals('https://testclient.com', $client->website);
    }

    /** @test */
    public function email_normalized_to_lowercase(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/clients', [
                'name' => 'Test Client',
                'email' => 'TEST@CLIENT.COM',
            ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('clients', [
            'email' => 'test@client.com',
        ]);
    }

    /** @test */
    public function admin_can_list_clients(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Client::factory()->count(25)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/clients');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'email'],
                ],
                'meta' => ['current_page', 'last_page', 'total'],
            ]);
    }

    /** @test */
    public function search_filters_clients(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Client::factory()->create(['name' => 'Acme Corp', 'email' => 'acme@example.com']);
        Client::factory()->create(['name' => 'Tech Solutions', 'email' => 'tech@example.com']);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/clients?search=acme');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function status_filter_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Client::factory()->count(5)->create(['is_active' => true]);
        Client::factory()->count(3)->create(['is_active' => false]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/clients?status=active');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function admin_can_update_client(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/clients/{$client->id}", [
                'phone' => '+9876543210',
                'notes' => 'Updated notes',
            ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'phone' => '+9876543210',
            'notes' => 'Updated notes',
        ]);
    }

    /** @test */
    public function default_client_name_cannot_be_changed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $defaultClient = Client::factory()->create([
            'name' => 'Tarqumi',
            'is_default' => true,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/clients/{$defaultClient->id}", [
                'name' => 'New Name',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function default_client_email_cannot_be_changed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $defaultClient = Client::factory()->create([
            'email' => 'info@tarqumi.com',
            'is_default' => true,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/clients/{$defaultClient->id}", [
                'email' => 'newemail@example.com',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function default_client_cannot_be_set_inactive(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $defaultClient = Client::factory()->create([
            'is_default' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/clients/{$defaultClient->id}", [
                'is_active' => false,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_active']);
    }

    /** @test */
    public function default_client_other_fields_can_be_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $defaultClient = Client::factory()->create([
            'is_default' => true,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/clients/{$defaultClient->id}", [
                'phone' => '+1234567890',
                'website' => 'https://tarqumi.com',
                'notes' => 'Updated notes',
            ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('clients', [
            'id' => $defaultClient->id,
            'phone' => '+1234567890',
        ]);
    }

    /** @test */
    public function admin_can_delete_regular_client(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create(['is_default' => false]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/clients/{$client->id}");

        $response->assertStatus(200);
        
        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }

    /** @test */
    public function default_client_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $defaultClient = Client::factory()->create(['is_default' => true]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/clients/{$defaultClient->id}");

        $response->assertStatus(400)
            ->assertJsonPath('success', false);
    }

    /** @test */
    public function deleted_client_can_be_restored(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create(['is_default' => false]);
        $client->delete();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/clients/{$client->id}/restore");

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function default_client_always_appears_first(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Client::factory()->create(['name' => 'Zebra Corp', 'is_default' => false]);
        Client::factory()->create(['name' => 'Tarqumi', 'is_default' => true]);
        Client::factory()->create(['name' => 'Alpha Corp', 'is_default' => false]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/clients?sort_by=name&sort_order=asc');

        $response->assertStatus(200);
        
        $firstClient = $response->json('data.0');
        $this->assertTrue($firstClient['is_default']);
    }

    /** @test */
    public function pagination_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Client::factory()->count(25)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/clients?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.per_page', 10);
    }

    /** @test */
    public function industry_filter_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Client::factory()->count(5)->create(['industry' => 'Technology']);
        Client::factory()->count(3)->create(['industry' => 'Finance']);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/clients?industry=Technology');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function has_projects_filter_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'admin']);
        
        $clientWithProjects = Client::factory()->create();
        $clientWithoutProjects = Client::factory()->create();
        
        $project = Project::factory()->create(['manager_id' => $manager->id]);
        $project->clients()->attach($clientWithProjects->id);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/clients?has_projects=true');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function created_by_and_updated_by_tracked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/clients', [
                'name' => 'Test Client',
                'email' => 'test@client.com',
            ]);

        $response->assertStatus(201);
        
        $client = Client::where('email', 'test@client.com')->first();
        $this->assertEquals($admin->id, $client->created_by);
        $this->assertEquals($admin->id, $client->updated_by);
    }
}
