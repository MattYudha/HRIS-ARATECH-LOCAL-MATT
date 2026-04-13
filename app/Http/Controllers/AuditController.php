<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isMasterAdmin()) {
            abort(403, 'Unauthorized: Only Master Admins can view Audit Trail.');
        }

        $logs = AuditLog::with('user', 'auditable')
            ->latest()
            ->paginate(20);

        return view('audit.index', compact('logs'));
    }
}
