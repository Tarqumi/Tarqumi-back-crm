<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeamManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    // CREATE TESTS
    public function test_admin_can_create_team_member(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/team', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'employee',
                'is_active' => true,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'email', 'role'],
                'message',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'employee',
        ]);
    }

    public function test_employee_cannot_create_team_member(): void
    {
        $employee = User::factory()->create(['role' => UserRole::EMPLOYEE]);

        $response = $this->actingAs($employee, 'sanctum')
            ->postJson('/api/v1/team', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'employee',
            ]);

        $response->assertStatus(403);
    }

    public function test_duplicate_email_rejected(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/team', [
                'name' => 'John Doe',
                'email' => 'existing@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'employee',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_profile_picture_upload_works(): void
    {
        $this->markTestSkipped('GD extension not installed - skip image test');
        
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/team', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'employee',
                'profile_picture' => $file,
            ]);

        $response->assertStatus(201);
        Storage::disk('public')->assertExists('profile-pictures/' . basename($response->json('data.profile_picture')));
    }

    // LIST TESTS
    public function test_admin_can_list_team_members(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        User::factory()->count(25)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/team');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'email', 'role'],
                ],
                'meta' => ['current_page', 'last_page', 'total'],
            ]);
    }

    public function test_search_filters_team_members(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/team?search=john');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_role_filter_works(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        User::factory()->count(5)->create(['role' => UserRole::EMPLOYEE]);
        User::factory()->count(3)->create(['role' => UserRole::HR]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/team?role=employee');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_status_filter_works(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        User::factory()->count(3)->create(['is_active' => true]);
        User::factory()->count(2)->create(['is_active' => false]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/team?status=active');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json('data')); // 3 + admin
    }

    // UPDATE TESTS
    public function test_admin_can_update_team_member(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $employee = User::factory()->create(['role' => UserRole::EMPLOYEE]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/team/{$employee->id}", [
                'name' => 'Updated Name',
                'department' => 'Engineering',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'name' => 'Updated Name',
            'department' => 'Engineering',
        ]);
    }

    public function test_admin_cannot_edit_super_admin(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $superAdmin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/team/{$superAdmin->id}", [
                'name' => 'Hacked Name',
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_escalate_own_privileges(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/team/{$admin->id}", [
                'role' => 'super_admin',
            ]);

        $response->assertStatus(403);
    }

    // DELETE TESTS
    public function test_cannot_delete_team_member_with_projects_without_reassignment(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $pm = User::factory()->create(['role' => UserRole::ADMIN]);
        $project = Project::factory()->create(['manager_id' => $pm->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/team/{$pm->id}");

        $response->assertStatus(422)
            ->assertJsonPath('managed_projects_count', 1);
    }

    public function test_can_delete_team_member_with_project_reassignment(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $pm = User::factory()->create(['role' => UserRole::ADMIN]);
        $newPm = User::factory()->create(['role' => UserRole::ADMIN]);
        $project = Project::factory()->create(['manager_id' => $pm->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/team/{$pm->id}", [
                'new_manager_id' => $newPm->id,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('reassigned_projects', 1);
        
        $this->assertSoftDeleted('users', ['id' => $pm->id]);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'manager_id' => $newPm->id,
        ]);
    }

    public function test_cannot_delete_last_super_admin(): void
    {
        $superAdmin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

        $response = $this->actingAs($superAdmin, 'sanctum')
            ->deleteJson("/api/v1/team/{$superAdmin->id}");

        $response->assertStatus(403);
    }

    public function test_can_delete_team_member_without_projects(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $employee = User::factory()->create(['role' => UserRole::EMPLOYEE]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/team/{$employee->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $employee->id]);
    }

    // DEPARTMENTS TEST
    public function test_can_get_departments_list(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN, 'department' => null]);
        User::factory()->create(['department' => 'Engineering']);
        User::factory()->create(['department' => 'Sales']);
        User::factory()->create(['department' => 'Engineering']); // Duplicate

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/team/departments');

        $response->assertStatus(200);
        $departments = $response->json('data');
        $this->assertCount(2, $departments); // Should only have 2 unique departments
        $this->assertContains('Engineering', $departments);
        $this->assertContains('Sales', $departments);
    }

    // PERMISSIONS TEST
    public function test_hr_can_create_but_not_delete(): void
    {
        $hr = User::factory()->create(['role' => UserRole::HR]);

        // Can create
        $response = $this->actingAs($hr, 'sanctum')
            ->postJson('/api/v1/team', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'employee',
            ]);

        $response->assertStatus(201);

        // Cannot delete
        $employee = User::factory()->create(['role' => UserRole::EMPLOYEE]);
        $response = $this->actingAs($hr, 'sanctum')
            ->deleteJson("/api/v1/team/{$employee->id}");

        $response->assertStatus(403);
    }
}
