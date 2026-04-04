<?php

namespace App\Constants;

class Roles
{
    // Role Titles
    const SUPER_ADMIN = 'Super Admin';
    const HR_ADMINISTRATOR = 'HR Administrator';
    const MANAGER_UNIT_HEAD = 'Manager / Unit Head';
    const SUPERVISOR = 'Supervisor';
    const EMPLOYEE = 'Employee';
    
    // Role Groups
    const ADMIN_ROLES = [self::SUPER_ADMIN, self::HR_ADMINISTRATOR];
    
    const SUPERVISOR_ROLES = [
        self::SUPER_ADMIN,
        self::HR_ADMINISTRATOR,
        self::MANAGER_UNIT_HEAD,
        self::SUPERVISOR
    ];
    
    const ALL_ROLES = [
        self::SUPER_ADMIN,
        self::HR_ADMINISTRATOR,
        self::MANAGER_UNIT_HEAD,
        self::SUPERVISOR,
        self::EMPLOYEE
    ];
    
    /**
     * Check if role is admin role
     */
    public static function isAdmin(string $role): bool
    {
        return in_array($role, self::ADMIN_ROLES);
    }
    
    /**
     * Check if role is supervisor role
     */
    public static function isSupervisor(string $role): bool
    {
        return in_array($role, self::SUPERVISOR_ROLES);
    }
    
    /**
     * Get all role names
     */
    public static function all(): array
    {
        return self::ALL_ROLES;
    }
}
