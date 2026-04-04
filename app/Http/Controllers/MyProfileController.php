<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || !$user->employee_id) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }

        $employee = Employee::with([
            'department', 
            'role', 
            'educationLevel', 
            'families', 
            'bankAccounts',
            'documentIdentities.identityType',
            'employeePositions.position',
            'mutations.oldDepartment',
            'mutations.newDepartment',
            'mutations.oldRole',
            'mutations.newRole'
        ])->findOrFail($user->employee_id);

        return view('profile.my-profile', compact('employee'));
    }
}
