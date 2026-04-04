<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Vendor::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm" role="group">';
                    $btns .= '<a href="'.route('vendors.edit', $row->id).'" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>';
                    $btns .= '<form action="'.route('vendors.destroy', $row->id).'" method="POST" class="d-inline">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm(\'Are you sure?\')"><i class="bi bi-trash"></i></button>
                              </form>';
                    $btns .= '</div>';
                    return $btns;
                })
                ->addColumn('status_badge', function($row){
                    $class = $row->status == 'active' ? 'bg-success' : 'bg-secondary';
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }
        return view('vendors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $vendor = Vendor::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor created successfully.',
                'data' => $vendor
            ]);
        }

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $vendor->update($request->all());

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        if ($vendor->procurements()->exists()) {
            return redirect()->route('vendors.index')->with('error', 'Cannot delete vendor: It has associated procurement records.');
        }
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
