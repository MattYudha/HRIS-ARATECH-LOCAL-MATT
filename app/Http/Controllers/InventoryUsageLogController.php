<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryUsageLog;
use App\Models\Inventory;
use App\Models\Employee;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class InventoryUsageLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();
            $employee = $user->employee;
            $role = $employee ? $employee->role->title : null; // Fetch fresh role

            $query = InventoryUsageLog::with(['inventory', 'employee']);

            // Filter based on role
            if (\App\Constants\Roles::isAdmin($role)) {
                // View all - no filter needed
            } elseif ($role && str_contains($role, 'Manager / Unit Head') && $employee) {
                // View own department (team)
                $query->whereHas('employee', function($q) use ($employee) {
                    $q->where('department_id', $employee->department_id);
                });
            } elseif ($employee) {
                // View self only (Employee, Employee, etc)
                $query->where('employee_id', $employee->id);
            } else {
                // Fallback for users without employee record (shouldn't happen usually)
                $query->where('id', 0);
            }
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) use ($role){
                    $btns = '<div class="btn-group btn-group-sm" role="group">';
                    $btns .= '<a href="'.route('inventory-usage-logs.show', $row->id).'" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>';
                    
                    if (\App\Constants\Roles::isAdmin(session('role'))) {
                        $btns .= '<a href="'.route('inventory-usage-logs.edit', $row->id).'" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>';
                        $btns .= '
                            <form action="'.route('inventory-usage-logs.destroy', $row->id).'" method="POST" class="d-inline">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(this.form)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        ';
                    }
                    $btns .= '</div>';
                    return $btns;
                })
                ->editColumn('borrowed_date', function($row){
                    return $row->borrowed_date ? Carbon::parse($row->borrowed_date)->format('d M Y H:i') : '-';
                })
                ->editColumn('returned_date', function($row){
                    return $row->returned_date ? Carbon::parse($row->returned_date)->format('d M Y H:i') : 'Not Returned';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('inventory-usage-logs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventories = Inventory::all();
        $employees = Employee::all();
        return view('inventory-usage-logs.create', compact('inventories', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'employee_id' => 'required|exists:employees,id',
            'borrowed_date' => 'required|date',
            'returned_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Parse datetime-local format (YYYY-MM-DDTHH:mm)
        $borrowedDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->borrowed_date);
        $returnedDate = $request->returned_date ? Carbon::createFromFormat('Y-m-d\TH:i', $request->returned_date) : null;

        InventoryUsageLog::create([
            'inventory_id' => $request->inventory_id,
            'employee_id' => $request->employee_id,
            'borrowed_date' => $borrowedDate,
            'returned_date' => $returnedDate,
            'notes' => $request->notes,
        ]);

        return redirect()->route('inventory-usage-logs.index')->with('success', 'Usage log created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $log = InventoryUsageLog::with('inventory', 'employee')->findOrFail($id);
        $this->verifyOwnership($log);
        return view('inventory-usage-logs.show', compact('log'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $log = InventoryUsageLog::findOrFail($id);
        $this->verifyOwnership($log);
        $inventories = Inventory::all();
        $employees = Employee::all();
        return view('inventory-usage-logs.edit', compact('log', 'inventories', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $log = InventoryUsageLog::findOrFail($id);
        $this->verifyOwnership($log);
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'employee_id' => 'required|exists:employees,id',
            'borrowed_date' => 'required|date',
            'returned_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Parse datetime-local format (YYYY-MM-DDTHH:mm)
        $borrowedDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->borrowed_date);
        $returnedDate = $request->returned_date ? Carbon::createFromFormat('Y-m-d\TH:i', $request->returned_date) : null;

        $log->update([
            'inventory_id' => $request->inventory_id,
            'employee_id' => $request->employee_id,
            'borrowed_date' => $borrowedDate,
            'returned_date' => $returnedDate,
            'notes' => $request->notes,
        ]);

        return redirect()->route('inventory-usage-logs.index')->with('success', 'Usage log updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $log = InventoryUsageLog::findOrFail($id);
        $this->verifyOwnership($log);
        $log->delete();
        return redirect()->route('inventory-usage-logs.index')->with('success', 'Usage log deleted successfully.');
    }

    private function verifyOwnership(InventoryUsageLog $log)
    {
        $user = auth()->user();
        $employee = $user->employee;
        $role = $employee ? $employee->role->title : null;

        if (\App\Constants\Roles::isAdmin($role)) {
            return;
        }

        if ($role && str_contains($role, 'Manager / Unit Head')) {
            if (!$employee || $log->employee->department_id != $employee->department_id) {
                abort(403, 'Unauthorized access to this log.');
            }
        } else {
            if (!$employee || $log->employee_id != $employee->id) {
                abort(403, 'Unauthorized access to this log.');
            }
        }
    }
}
