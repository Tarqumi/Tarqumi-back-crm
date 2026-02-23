<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case FOUNDER = 'founder';
    case HR = 'hr';
    case EMPLOYEE = 'employee';

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::FOUNDER => 'Founder',
            self::HR => 'HR',
            self::EMPLOYEE => 'Employee',
        };
    }

    public function canManageTeam(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN, self::HR]);
    }

    public function canManageClients(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
    }

    public function canManageProjects(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
    }

    public function canDeleteAdmin(): bool
    {
        return $this === self::SUPER_ADMIN;
    }

    public function canViewCRM(): bool
    {
        return true; // All roles can view CRM data in Phase 1
    }
}
