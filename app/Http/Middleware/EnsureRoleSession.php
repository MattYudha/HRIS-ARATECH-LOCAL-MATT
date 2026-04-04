<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRoleSession
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->employee && $user->employee->role) {
            // Refresh session role/employee_id if missing or stale
            if (session('role') !== $user->employee->role->title) {
                session(['role' => $user->employee->role->title]);
            }
            if (session('employee_id') !== $user->employee->id) {
                session(['employee_id' => $user->employee->id]);
            }
        }
        return $next($request);
    }
}
