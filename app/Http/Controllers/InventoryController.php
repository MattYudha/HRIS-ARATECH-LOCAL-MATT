<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\InventoryCategory;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Inventory::with('category');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function($row){
                    if($row->image_path){
                        $url = asset('storage/' . $row->image_path);
                        return '<img src="'.$url.'" alt="Img" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">';
                    }
                    return '<span class="text-muted">No Img</span>';
                })
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm" role="group">';
                    $btns .= '<a href="'.route('inventories.show', $row->id).'" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>';
                    
                    if (\App\Constants\Roles::isAdmin(session('role'))) {
                        $btns .= '<a href="'.route('inventories.edit', $row->id).'" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>';
                        $btns .= '
                            <form action="'.route('inventories.destroy', $row->id).'" method="POST" class="d-inline" id="delete-form-'.$row->id.'">
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
                ->addColumn('status_badge', function($row){
                    $class = match($row->status) {
                        'active' => 'bg-success',
                        'inactive' => 'bg-warning text-dark',
                        'damaged' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->editColumn('quantity', function($row){
                   $qty = $row->quantity;
                   if($row->min_stock_threshold > 0 && $qty <= $row->min_stock_threshold){
                       return '<span class="text-danger fw-bold">'.$qty.' <i class="bi bi-exclamation-circle-fill" title="Low Stock"></i></span>';
                   }
                   return $qty;
                })
                ->addColumn('item_type_label', function($row){
                $label = $row->item_type == 'habis_pakai' ? 'Habis Pakai' : 'Tidak Habis Pakai';
                $class = $row->item_type == 'habis_pakai' ? 'bg-light-info text-info' : 'bg-light-primary text-primary';
                return '<span class="badge '.$class.'">'.$label.'</span>';
            })
            ->rawColumns(['image', 'action', 'status_badge', 'quantity', 'item_type_label'])
                ->make(true);
        }
        return view('inventories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = InventoryCategory::all();
        return view('inventories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventory_category_id' => 'required|exists:inventory_categories,id',
            'item_type' => 'required|in:habis_pakai,tidak_habis_pakai',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'min_stock_threshold' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,damaged',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $data = $request->except('image');
        $data['min_stock_threshold'] = $request->input('min_stock_threshold', 0);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('inventory-images', 'public');
            $data['image_path'] = $path;
        }

        Inventory::create($data);

        return redirect()->route('inventories.index')->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventory = Inventory::with('category', 'usageLogs')->findOrFail($id);
        return view('inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $categories = InventoryCategory::all();
        return view('inventories.edit', compact('inventory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $request->validate([
            'inventory_category_id' => 'required|exists:inventory_categories,id',
            'item_type' => 'required|in:habis_pakai,tidak_habis_pakai',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'min_stock_threshold' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,damaged',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');
        $data['min_stock_threshold'] = $request->input('min_stock_threshold', 0);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($inventory->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($inventory->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($inventory->image_path);
            }
            
            $path = $request->file('image')->store('inventory-images', 'public');
            $data['image_path'] = $path;
        }

        $inventory->update($data);

        return redirect()->route('inventories.index')->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();
        return redirect()->route('inventories.index')->with('success', 'Inventory item deleted successfully.');
    }
}
