<?php

namespace Tests\Feature;

use App\Enums\FounderRole;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_has_all_permissions(): void
    {
        $superAdmin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);

        $response = $this->actingAs($superAdmin, 'sanctum')
            ->getJson('/api/v1/permissions');

        $response->assertStatus(200);
        $permissions = $response->json('data.permissions');

        $this->assertTrue($permissions['can_manage_team']);
        $this->assertTrue($permissions['can_delete_admin']);
        $this->assertTrue($permissions['can_manage_clients']);
        $this->assertTrue($permissions['can_manage_projects']);
        $this->assertTrue($permissions['can_edit_landing_page']);
    }

    public function test_admin_has_most_permissions_except_delete_admin(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/permissions');

        $response->assertStatus(200);
        $permissions = $response->json('data.permissions');

        $this->assertTrue($permissions['can_manage_team']);
        $this->assertFalse($permissions['can_delete_admin']);
        $this->assertTrue($permissions['can_manage_clients']);
        $this->assertTrue($permissions['can_manage_projects']);
        $this->assertTrue($permissions['can_edit_landing_page']);
    }

    public function test_cto_can_edit_landing_page(): void
    {
        $cto = User::factory()->create([
            'role' => UserRole::FOUNDER,
            'founder_role' => FounderRole::CTO,
        ]);

        $response = $this->actingAs($cto, 'sanctum')
            ->getJson('/api/v1/permissions');

        $response->assertStatus(200);
        $permissions = $response->json('data.permissions');

        $this->assertTrue($permissions['can_edit_landing_page']);
        $this->assertTrue($permissions['can_manage_blog']);
        $this->assertFalse($permissions['can_manage_clients']);
        $this->assertFalse($permissions['can_manage_projects']);
    }

    public function test_ceo_cannot_edit_landing_page(): void
    {
        $ceo = User::factory()->create([
            'role' => UserRole::FOUNDER,
            'founder_role' => FounderRole::CEO,
        ]);

        $response = $this->actingAs($ceo, 'sanctum')
            ->getJson('/api/v1/permissions');

        $response->assertStatus(200);
        $permissions = $response->json('data.permissions');

        $this->assertFalse($permissions['can_edit_landing_page']);
        $this->assertFalse($permissions['can_manage_blog']);
        $this->assertFalse($permissions['can_manage_clients']);
        $this->assertFalse($permissions['can_manage_projects']);
    }

    public function test_hr_can_manage_team_but_not_clients(): void
    {
        $hr = User::factory()->create(['role' => UserRole::HR]);

        $response = $this->actingAs($hr, 'sanctum')
            ->getJson('/api/v1/permissions');

        $response->assertStatus(200);
        $permissions = $response->json('data.permissions');

        $this->assertTrue($permissions['can_manage_team']);
        $this->assertFalse($permissions['can_manage_clients']);
        $this->assertFalse($permissions['can_manage_projects']);
        $this->assertFalse($permissions['can_edit_landing_page']);
    }

    public function test_employee_has_minimal_permissions(): void
    {
        $employee = User::factory()->create(['role' => UserRole::EMPLOYEE]);

        $response = $this->actingAs($employee, 'sanctum')
            ->getJson('/api/v1/permissions');

        $response->assertStatus(200);
        $permissions = $response->json('data.permissions');

        $this->assertFalse($permissions['can_manage_team']);
        $this->assertFalse($permissions['can_manage_clients']);
        $this->assertFalse($permissions['can_manage_projects']);
        $this->assertFalse($permissions['can_edit_landing_page']);
    }
}
