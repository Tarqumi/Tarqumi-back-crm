<?php

namespace App\Models;

use App\Enums\FounderRole;
use App\Enums\UserRole;
use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasPermissions, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'whatsapp',
        'department',
        'job_title',
        'profile_picture',
        'role',
        'founder_role',
        'is_active',
        'last_login_at',
        'last_active_at',
        'inactive_days',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_active_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
            'inactive_days' => 'integer',
            'role' => UserRole::class,
            'founder_role' => FounderRole::class,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['super_admin', 'admin']);
    }

    public function scopeFounders($query)
    {
        return $query->where('role', 'founder');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsFounderAttribute(): bool
    {
        return $this->role === UserRole::FOUNDER;
    }

    public function getIsSuperAdminAttribute(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    public function getIsAdminAttribute(): bool
    {
        return in_array($this->role, [
            UserRole::SUPER_ADMIN,
            UserRole::ADMIN,
        ]);
    }

    public function getCanEditLandingPageAttribute(): bool
    {
        return $this->is_admin ||
               ($this->is_founder && $this->founder_role === FounderRole::CTO->value);
    }

    public function getCanViewContactSubmissionsAttribute(): bool
    {
        return $this->is_admin ||
               ($this->is_founder && $this->founder_role === FounderRole::CTO);
    }

    /**
     * Get all permissions for the authenticated user.
     * Used by PermissionsController to return to frontend.
     */
    public function getAllPermissions(): array
    {
        return [
            'can_manage_team' => $this->canManageTeam(),
            'can_create_team_member' => $this->canCreateTeamMember(),
            'can_edit_team_member' => $this->canEditTeamMember(),
            'can_delete_team_member' => $this->canDeleteTeamMember(),
            'can_delete_admin' => $this->canDeleteAdmin(),
            'can_manage_clients' => $this->canManageClients(),
            'can_view_clients' => $this->canViewClients(),
            'can_manage_projects' => $this->canManageProjects(),
            'can_view_projects' => $this->canViewProjects(),
            'can_edit_landing_page' => $this->canEditLandingPage(),
            'can_manage_blog' => $this->canManageBlog(),
            'can_view_contact_submissions' => $this->canViewContactSubmissions(),
            'can_delete_contact_submissions' => $this->canDeleteContactSubmissions(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function managedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function createdClients(): HasMany
    {
        return $this->hasMany(Client::class, 'created_by');
    }

    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }
}
