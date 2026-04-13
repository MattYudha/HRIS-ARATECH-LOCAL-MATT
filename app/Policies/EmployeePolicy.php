<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All active employees can view the employee directory
        return $user->employee_id !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Employee $employee): bool
    {
        // Can view if:
        // 1. Is Admin
        // 2. Is Manager / Unit Head of same department
        // 3. Is Supervisor of employee
        // 4. Is Same Department
        // 5. Is Self
        
        if ($user->canManage($employee)) {
            return true;
        }
        
        return $user->isSameDepartment($employee);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Employee $employee): bool
    {
        // Can update if:
        // 1. Is Admin (HR Administrator/Master Admin)
        // 2. Is Manager / Unit Head of same department
        // 3. Is direct Supervisor
        // 4. Is Self (Profile update)
        
        return $user->canManage($employee);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Employee $employee): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Employee $employee): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Employee $employee): bool
    {
        return $user->isAdmin();
    }
}
