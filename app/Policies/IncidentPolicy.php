<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncidentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All active employees can access the module, but the controller will scope the data
        return $user->employee_id !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Incident $incident): bool
    {
        // Can view if:
        // 1. Is Admin
        // 2. Is Manager / Unit Head of the employee involved
        // 3. Is the reported employee
        // 4. Is the reporter
        
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->employee_id == $incident->employee_id || $user->id == $incident->reported_by) {
            return true;
        }
        
        if ($user->isManager() && $user->employee->department_id == $incident->employee->department_id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only HR Administrator, Super Admin, or Manager / Unit Heads can record incidents/awards
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Incident $incident): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager() && $user->employee && $user->employee->department_id == $incident->employee->department_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Incident $incident): bool
    {
        // Only HR Administrator and Super Admin can delete incident records
        return $user->isAdmin();
    }
}
