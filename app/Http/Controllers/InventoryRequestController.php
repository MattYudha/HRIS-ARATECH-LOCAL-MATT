<?php

namespace App\Http\Controllers;

use App\Models\InventoryRequest;
use App\Models\Inventory;
use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InventoryRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $role = session('role');
            $query = InventoryRequest::with(['employee', 'inventory']);
            
            if (in_array($role, ['HR Administrator', 'Super Admin'])) {
                // Full access
            } elseif ($role === 'Manager / Unit Head') {
                $deptId = auth()->user()->employee->department_id;
                $query->whereHas('employee', function($q) use ($deptId) {
                    $q->where('department_id', $deptId);
                });
            } else {
                $query->where('employee_id', session('employee_id'));
            }
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm" role="group">';
                    $btns .= '<a href="'.route('inventory-requests.show', $row->id).'" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>';
                    
                    $role = session('role');
                    $isAdmin = in_array($role, ['HR Administrator', 'Super Admin']);
                    
                    if($isAdmin || $row->employee_id == session('employee_id')) {
                         $btns .= '<a href="'.route('inventory-requests.edit', $row->id).'" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                    }

                    if($row->status == 'pending' && $isAdmin){
                        $btns .= '<a href="'.route('inventory-requests.approve', $row->id).'" class="btn btn-outline-success" title="Approve"><i class="bi bi-check-lg"></i></a>';
                        $btns .= '<a href="'.route('inventory-requests.reject', $row->id).'" class="btn btn-outline-danger" title="Reject"><i class="bi bi-x-lg"></i></a>';
                    }
                    
                    if($isAdmin) {
                        $btns .= '<form action="'.route('inventory-requests.destroy', $row->id).'" method="POST" class="d-inline">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                    <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete(this.form)"><i class="bi bi-trash"></i></button>
                                  </form>';
                    }

                    $btns .= '</div>';
                    return $btns;
                })
                ->addColumn('status_badge', function($row){
                    $class = match($row->status) {
                        'approved' => 'bg-info',
                        'completed' => 'bg-success',
                        'pending' => 'bg-warning text-dark',
                        'rejected' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('item_display', function($row){
                    return $row->inventory ? $row->inventory->name : $row->item_name;
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at->format('d M Y H:i');
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }
        
        return view('inventory_requests.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventories = Inventory::where('status', 'active')->get();
        return view('inventory_requests.create', compact('inventories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'request_type' => 'required|in:new,repair,replacement',
            'inventory_id' => 'nullable|exists:inventories,id',
            'item_name' => 'nullable|required_if:inventory_id,null|string|max:255',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
        ]);

        $data = $request->all();
        $data['employee_id'] = session('employee_id');
        $data['status'] = 'pending';

        InventoryRequest::create($data);

        return redirect()->route('inventory-requests.index')->with('success', 'Request inventory berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryRequest $inventoryRequest)
    {
        $inventoryRequest->load(['employee', 'inventory', 'approvedBy']);
        return view('inventory_requests.show', compact('inventoryRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryRequest $inventoryRequest)
    {
        $inventories = Inventory::all();
        return view('inventory_requests.edit', compact('inventoryRequest', 'inventories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryRequest $inventoryRequest)
    {
        $role = session('role');
        $isAdmin = in_array($role, ['HR Administrator', 'Super Admin']);

        $rules = [
            'request_type' => 'required|in:new,repair,replacement',
            'inventory_id' => 'nullable|exists:inventories,id',
            'item_name' => 'nullable|required_if:inventory_id,null|string|max:255',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
        ];

        if ($isAdmin) {
            $rules['status'] = 'required|in:pending,approved,rejected,completed';
            $rules['notes'] = 'nullable|string';
        }

        $request->validate($rules);

        $data = $request->all();
        
        if ($isAdmin) {
            if ($request->status != $inventoryRequest->status && in_array($request->status, ['approved', 'rejected', 'completed'])) {
                // Stock Deduction Logic for manual update
                if ($request->status === 'approved' && $inventoryRequest->status === 'pending') {
                     if ($inventoryRequest->inventory_id) {
                        $inventory = Inventory::find($inventoryRequest->inventory_id);
                        if (!$inventory || $inventory->quantity < $inventoryRequest->quantity) {
                            return redirect()->back()->with('error', 'Stok inventory tidak mencukupi.');
                        }
                        $inventory->decrement('quantity', $inventoryRequest->quantity);

                         // Create Usage Log
                        \App\Models\InventoryUsageLog::create([
                            'inventory_id' => $inventoryRequest->inventory_id,
                            'employee_id' => $inventoryRequest->employee_id,
                            'borrowed_date' => now(),
                            'notes' => 'Auto-generated from Request #' . $inventoryRequest->id . ': ' . $inventoryRequest->reason
                        ]);
                    }
                }

                $data['approved_by'] = session('employee_id');
                $data['approved_at'] = now();
            }
        } else {
            // Non-admins cannot update status or notes
            unset($data['status']);
            unset($data['notes']);
            unset($data['approved_by']);
            unset($data['approved_at']);
        }

        $inventoryRequest->update($data);

        return redirect()->route('inventory-requests.index')->with('success', 'Request inventory berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryRequest $inventoryRequest)
    {
        $inventoryRequest->delete();
        return redirect()->route('inventory-requests.index')->with('success', 'Request inventory berhasil dihapus.');
    }

    public function approve($id)
    {
        $req = InventoryRequest::findOrFail($id);
        
        // Validation: Check stock if approval involves an inventory item
        if ($req->inventory_id && $req->status === 'pending') {
            $inventory = Inventory::find($req->inventory_id);
            if (!$inventory || $inventory->quantity < $req->quantity) {
                return redirect()->back()->with('error', 'Stok inventory tidak mencukupi untuk menyetujui request ini.');
            }
            
            // Decrement Stock
            $inventory->decrement('quantity', $req->quantity);
            
            // Create Usage Log
            \App\Models\InventoryUsageLog::create([
                'inventory_id' => $req->inventory_id,
                'employee_id' => $req->employee_id,
                'borrowed_date' => now(),
                'notes' => 'Auto-generated from Request #' . $req->id . ': ' . $req->reason
            ]);
        }

        $req->update([
            'status' => 'approved',
            'approved_by' => session('employee_id'),
            'approved_at' => now(),
        ]);

        return redirect()->route('inventory-requests.index')->with('success', 'Request inventory disetujui, stok berkurang, dan log penggunaan dibuat.');
    }

    public function reject($id)
    {
        $req = InventoryRequest::findOrFail($id);
        $req->update([
            'status' => 'rejected',
            'approved_by' => session('employee_id'),
            'approved_at' => now(),
        ]);

        return redirect()->route('inventory-requests.index')->with('success', 'Request inventory ditolak.');
    }
}
