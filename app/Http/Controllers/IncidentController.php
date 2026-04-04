<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Incident::class);

        $user = auth()->user();
        $query = Incident::with(['employee.department', 'reportedBy.employee']);
        
        // Role-based scoping
        if (!$user->isAdmin()) {
            if ($user->isManager()) {
                // Manager / Unit Heads see incidents in their department
                $query->whereHas('employee', function($q) use ($user) {
                    $q->where('department_id', $user->employee->department_id);
                });
            } else {
                // Others only see incidents involving them or reported by them
                $query->where(function($q) use ($user) {
                    $q->where('employee_id', $user->employee_id)
                      ->orWhere('reported_by', $user->id);
                });
            }
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $incidents = $query->latest('incident_date')->paginate(10);
        return view('incidents.index', compact('incidents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Incident::class);

        $employees = Employee::orderBy('fullname')->get();
        return view('incidents.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Incident::class);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string',
            'incident_date' => 'required|date',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,investigating,resolved,closed',
            'action_taken' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['reported_by'] = auth()->id();
        
        Incident::create($validated);

        return redirect()->route('incidents.index')->with('success', 'Incident recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Incident $incident)
    {
        $this->authorize('view', $incident);

        $incident->loadMissing([
            'employee.department',
            'reportedBy.employee.department',
            'resolvedBy.employee.department',
        ]);

        return view('incidents.show', compact('incident'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Incident $incident)
    {
        $this->authorize('update', $incident);

        $employees = Employee::orderBy('fullname')->get();
        return view('incidents.edit', compact('incident', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incident $incident)
    {
        $this->authorize('update', $incident);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string',
            'incident_date' => 'required|date',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,investigating,resolved,closed',
            'action_taken' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] == 'resolved' && $incident->status != 'resolved') {
            $validated['resolved_by'] = auth()->id();
            $validated['resolved_at'] = now();
        }

        $incident->update($validated);

        return redirect()->route('incidents.index')->with('success', 'Incident updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incident $incident)
    {
        $this->authorize('delete', $incident);

        $incident->delete();
        return redirect()->route('incidents.index')->with('success', 'Incident deleted successfully.');
    }
}
