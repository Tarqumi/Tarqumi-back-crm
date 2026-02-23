<?php

namespace App\Traits;

use App\Enums\UserRole;
use App\Enums\FounderRole;

trait HasPermissions
{
    // Team Management Permissions
    public function canManageTeam(): bool
    {
        return $this->role->canManageTeam();
    }

    public function canCreateTeamMember(): bool
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::HR]);
    }

    public function canEditTeamMember(): bool
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::HR]);
    }

    public function canDeleteTeamMember(): bool
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN]);
    }

    public function canDeleteAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    // Client Management Permissions
    public function canManageClients(): bool
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN]);
    }

    public function canViewClients(): bool
    {
        return true; // All roles can view clients in Phase 1
    }

    // Project Management Permissions
    public function canManageProjects(): bool
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN]);
    }

    public function canViewProjects(): bool
    {
        return true; // All roles can view projects in Phase 1
    }

    // Landing Page Permissions
    public function canEditLandingPage(): bool
    {
        if (in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN])) {
            return true;
        }

        if ($this->role === UserRole::FOUNDER && $this->founder_role === FounderRole::CTO) {
            return true;
        }

        return false;
    }

    public function canManageBlog(): bool
    {
        return $this->canEditLandingPage();
    }

    // Contact Submissions Permissions
    public function canViewContactSubmissions(): bool
    {
        if (in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN])) {
            return true;
        }

        if ($this->role === UserRole::FOUNDER && $this->founder_role === FounderRole::CTO) {
            return true;
        }

        return false;
    }

    public function canDeleteContactSubmissions(): bool
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN]);
    }
}
