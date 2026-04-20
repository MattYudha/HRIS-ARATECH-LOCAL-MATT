<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = auth()->user();
        
        // Validate user exists
        if (!$user) {
            abort(401, 'Unauthenticated');
        }
        
        // Get employee ID
        $employeeID = $user->employee_id;
        
        // Check if employee ID exists
        if (!$employeeID) {
            \Log::warning('User without employee_id attempted access', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            abort(403, 'No employee profile associated with this user');
        }
        
        // Find employee
        $employee = Employee::find($employeeID);
        
        // Validate employee exists
        if (!$employee) {
            \Log::error('Employee not found for user', [
                'user_id' => $user->id,
                'employee_id' => $employeeID
            ]);
            abort(403, 'Employee profile not found');
        }
        
        // Validate employee has role
        if (!$employee->role) {
            \Log::error('Employee without role attempted access', [
                'employee_id' => $employee->id
            ]);
            abort(403, 'No role assigned to employee');
        }

        // Store in session for backward compatibility
        $request->session()->put('role', $employee->role->title);
        $request->session()->put('employee_id', $employee->id);
        
        $checkRoles = $roles;

        // Use trimmed and case-insensitive check if possible, but at least trim
        $checkUserRole = trim($employee->role->title);
        $checkRolesTrimmer = array_map('trim', $checkRoles);
        $hasRole = in_array($checkUserRole, $checkRolesTrimmer);
        $hasAccess = false;
        
        // If it looks like a module key (lowercase, underscore), check if employee has that access
        foreach ($roles as $r) {
            if (preg_match('/^[a-z_]+$/', $r) && is_array($employee->role->access) && in_array($r, $employee->role->access)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasRole && !$hasAccess) {
            \Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'employee_role' => $employee->role->title,
                'required_roles_or_access' => $roles,
                'url' => $request->url()
            ]);
            abort(403, 'Unauthorized action for your role');
        }

        return $next($request);
    }
}
