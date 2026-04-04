<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized: Only Super Admins can manage system.');
        }

        return view('system.index');
    }

    public function backup()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        // Logic placeholder for real backup
        return back()->with('success', 'Database backup triggered successfully (Dummy Notification)');
    }
}
