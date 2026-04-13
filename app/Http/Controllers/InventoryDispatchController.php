<?php

namespace App\Http\Controllers;

use App\Models\InventoryDispatch;
use App\Models\Inventory;
use App\Models\Employee;
use App\Models\LogisticsShipment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

class InventoryDispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = InventoryDispatch::with(['inventory', 'employee']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm" role="group">';
                    $btns .= '<a href="'.route('inventory-dispatches.show', $row->id).'" class="btn btn-outline-info" title="Detail / Barcode"><i class="bi bi-eye"></i></a>';
                    
                    if (\App\Constants\Roles::isAdmin(session('role'))) {
                        $btns .= '<form action="'.route('inventory-dispatches.destroy', $row->id).'" method="POST" class="d-inline">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(this.form)"><i class="bi bi-trash"></i></button>
                              </form>';
                    }
                    $btns .= '</div>';
                    return $btns;
                })
                ->addColumn('area_room', function($row){
                    return ($row->area ?? '-') . ' / ' . ($row->room ?? '-');
                })
                ->addColumn('barcode_display', function($row){
                    return '<code>' . $row->barcode . '</code>';
                })
                ->editColumn('dispatch_date', function($row){
                    return $row->dispatch_date->format('d M Y H:i');
                })
                ->rawColumns(['action', 'barcode_display', 'area_room'])
                ->make(true);
        }
        return view('inventory_dispatches.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventories = Inventory::where('quantity', '>', 0)->get();
        $employees = Employee::with('department')->orderBy('fullname')->get();
        return view('inventory_dispatches.create', compact('inventories', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'employee_id' => 'required|exists:employees,id',
            'quantity' => 'required|integer|min:1',
            'area' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'dispatch_date' => 'required|date',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);
        
        if ($inventory->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        DB::transaction(function() use ($request, $inventory) {
            // Deduct stock
            $inventory->decrement('quantity', $request->quantity);

            // Generate Barcode code
            $barcode = 'INV-DISP-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

            // Create dispatch record
            $dispatch = InventoryDispatch::create([
                'inventory_id' => $request->inventory_id,
                'employee_id' => $request->employee_id,
                'quantity' => $request->quantity,
                'area' => $request->area,
                'room' => $request->room,
                'dispatch_date' => $request->dispatch_date,
                'barcode' => $barcode,
                'status' => 'deployed',
            ]);

            // Create logistics shipment record
            LogisticsShipment::create([
                'trackable_id' => $dispatch->id,
                'trackable_type' => InventoryDispatch::class,
                'origin' => 'Warehouse / Central Hub',
                'destination' => ($request->area ?? '-') . ' / ' . ($request->room ?? '-'),
                'status' => 'pending',
                'estimated_arrival' => now(), // Usually immediate for local dispatch
            ]);
        });

        return redirect()->route('inventory-dispatches.index')->with('success', 'Barang berhasil dikeluarkan dan barcode telah dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryDispatch $inventoryDispatch)
    {
        $inventoryDispatch->load(['inventory', 'employee']);
        $dispatch = $inventoryDispatch;
        return view('inventory_dispatches.show', compact('dispatch'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryDispatch $inventoryDispatch)
    {
        // Revert stock? User requirement didn't specify, but usually dispatches are permanent or consumed.
        // For simplicity, we just delete the record here.
        $inventoryDispatch->delete();
        return redirect()->route('inventory-dispatches.index')->with('success', 'Dispatch record deleted.');
    }
}
