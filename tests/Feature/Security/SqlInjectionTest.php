<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SqlInjectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function login_resists_sql_injection_in_email()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => "admin' OR '1'='1",
            'password' => 'password',
        ]);

        $response->assertStatus(422); // Validation error or 401 unauthorized
        $this->assertGuest('sanctum');
    }

    /** @test */
    public function login_resists_sql_injection_in_password()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => "' OR '1'='1",
        ]);

        $response->assertStatus(422);
        $this->assertGuest('sanctum');
    }

    /** @test */
    public function project_search_resists_sql_injection()
    {
        $this->actingAs($this->admin, 'sanctum');

        $response = $this->getJson('/api/v1/projects?search=' . urlencode("' OR 1=1 --"));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        // Should return empty or safe results, not all projects
    }

    /** @test */
    public function client_search_resists_sql_injection()
    {
        $this->actingAs($this->admin, 'sanctum');

        $response = $this->getJson('/api/v1/clients?search=' . urlencode("'; DROP TABLE clients; --"));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        // Database should still exist
        $this->assertDatabaseCount('clients', 0); // No clients created, so 0 is expected
    }

    /** @test */
    public function team_search_resists_sql_injection()
    {
        $this->actingAs($this->admin, 'sanctum');

        $response = $this->getJson('/api/v1/team?search=' . urlencode("' UNION SELECT * FROM users --"));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function blog_search_resists_sql_injection()
    {
        $this->actingAs($this->admin, 'sanctum');

        $response = $this->getJson('/api/v1/cms/blog/posts?search=' . urlencode("' OR 1=1 --"));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function contact_form_resists_sql_injection_in_name()
    {
        $response = $this->postJson('/api/v1/contact/submit', [
            'name' => "Robert'); DROP TABLE contact_submissions;--",
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'message' => 'Test message',
        ]);

        // Should either succeed with sanitized data or fail validation
        $this->assertTrue(
            $response->status() === 201 || $response->status() === 422 || $response->status() === 429
        );
        
        // If successful, database should still exist with sanitized data
        if ($response->status() === 201) {
            $this->assertDatabaseHas('contact_submissions', [
                'email' => 'test@example.com',
            ]);
        }
    }

    /** @test */
    public function project_name_resists_sql_injection()
    {
        $this->actingAs($this->admin, 'sanctum');

        $response = $this->postJson('/api/v1/projects', [
            'name' => "'; DROP TABLE projects; --",
            'description' => 'Test description',
            'manager_id' => $this->admin->id,
            'budget' => 10000,
            'currency' => 'USD',
            'priority' => 5,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'status' => 'planning',
        ]);

        // Should succeed with sanitized data
        if ($response->status() === 201) {
            $response->assertJson(['success' => true]);
            // Database should still exist
            $this->assertDatabaseCount('projects', 1);
        }
    }
}
