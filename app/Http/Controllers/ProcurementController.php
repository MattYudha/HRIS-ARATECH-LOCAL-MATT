<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\Vendor;
use App\Models\Inventory;
use App\Models\LogisticsShipment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Procurement::with(['vendor', 'requester']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm" role="group">';
                    $btns .= '<a href="'.route('procurements.show', $row->id).'" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>';
                    
                    if ($row->status == 'pending') {
                        $btns .= '<a href="'.route('procurements.edit', $row->id).'" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>';
                        $btns .= '<a href="'.route('procurements.order', $row->id).'" class="btn btn-outline-primary" title="Mark as Ordered"><i class="bi bi-send"></i></a>';
                    }

                    if ($row->status == 'ordered') {
                        $btns .= '<a href="'.route('procurements.receive', $row->id).'" class="btn btn-outline-success" title="Mark as Received (Restock)"><i class="bi bi-box-seam"></i></a>';
                    }

                    if (\App\Constants\Roles::isAdmin(session('role'))) {
                         $btns .= '<form action="'.route('procurements.destroy', $row->id).'" method="POST" class="d-inline">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(this.form)"><i class="bi bi-trash"></i></button>
                              </form>';
                    }
                    $btns .= '</div>';
                    return $btns;
                })
                ->addColumn('status_badge', function($row){
                    $class = match($row->status) {
                        'pending' => 'bg-warning text-dark',
                        'ordered' => 'bg-primary',
                        'received' => 'bg-success',
                        'cancelled' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->editColumn('order_date', function($row){
                    return $row->order_date->format('d M Y');
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }
        return view('procurements.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::where('status', 'active')->get();
        $inventories = Inventory::all();
        return view('procurements.create', compact('vendors', 'inventories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.inventory_id' => 'nullable|exists:inventories,id',
            'items.*.item_name' => 'required_without:items.*.inventory_id|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function() use ($request) {
            $procurement = Procurement::create([
                'vendor_id' => $request->vendor_id,
                'employee_id' => session('employee_id'),
                'po_number' => 'PO-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))),
                'order_date' => $request->order_date,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $total += $subtotal;

                ProcurementItem::create([
                    'procurement_id' => $procurement->id,
                    'inventory_id' => $item['inventory_id'] ?? null,
                    'item_name' => $item['item_name'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $procurement->update(['total_amount' => $total]);
        });

        return redirect()->route('procurements.index')->with('success', 'Procurement request created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Procurement $procurement)
    {
        $procurement->load(['vendor', 'requester', 'items.inventory']);
        return view('procurements.show', compact('procurement'));
    }

    public function markAsOrdered($id)
    {
        $procurement = Procurement::findOrFail($id);
        $procurement->update(['status' => 'ordered']);

        // Create logistics shipment record
        LogisticsShipment::create([
            'trackable_id' => $procurement->id,
            'trackable_type' => Procurement::class,
            'origin' => $procurement->vendor->address ?? $procurement->vendor->name,
            'destination' => 'Warehouse / Hub Utama',
            'status' => 'pending',
            'estimated_arrival' => now()->addDays(3), // Default estimate
        ]);

        return redirect()->back()->with('success', 'Procurement marked as Ordered and shipment tracking initialized.');
    }

    public function receive($id)
    {
        $procurement = Procurement::findOrFail($id);
        
        if ($procurement->status === 'received') {
            return redirect()->back()->with('error', 'This procurement has already been received.');
        }

        DB::transaction(function() use ($procurement) {
            foreach ($procurement->items as $item) {
                if ($item->inventory_id) {
                    $inventory = Inventory::find($item->inventory_id);
                    $inventory->increment('quantity', $item->quantity);
                } else {
                    // Logic to create new inventory item could go here if needed
                }
            }
            $procurement->update(['status' => 'received']);
        });

        return redirect()->back()->with('success', 'Items received and stock updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procurement $procurement)
    {
        $procurement->delete();
        return redirect()->route('procurements.index')->with('success', 'Procurement deleted.');
    }
}
