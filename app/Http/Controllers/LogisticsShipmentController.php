<?php

namespace App\Http\Controllers;

use App\Models\LogisticsShipment;
use App\Models\Procurement;
use App\Models\InventoryDispatch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LogisticsShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = LogisticsShipment::with([
                'trackable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Procurement::class => ['vendor'],
                        InventoryDispatch::class => ['inventory'],
                    ]);
                }
            ]);

            return DataTables::of($data)
                ->addIndexColumn()

                // 🔥 ACTION BUTTON
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm">';

                    if ($row->trackable instanceof Procurement) {
                        $btns .= '<a href="'.route('procurements.show', $row->trackable_id).'" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>';
                    } elseif ($row->trackable instanceof InventoryDispatch) {
                        $btns .= '<a href="'.route('inventory-dispatches.show', $row->trackable_id).'" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>';
                    }

$btns .= '<a href="'.route('logistics-shipments.edit', $row->id).'" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>';                    $btns .= '</div>';

                    return $btns;
                })

                // 🔥 STATUS BADGE
                ->addColumn('status_badge', function($row){
                    $class = match($row->status) {
                        'pending' => 'bg-warning text-dark',
                        'in_transit' => 'bg-primary',
                        'delivered' => 'bg-success',
                        'cancelled' => 'bg-danger',
                        default => 'bg-secondary'
                    };

                    return '<span class="badge '.$class.'">'.ucfirst(str_replace('_', ' ', $row->status)).'</span>';
                })

                // 🔥 RELATED TO (FIX UTAMA DI SINI)
                ->addColumn('related_to', function($row){
                    if (!$row->trackable) {
                        return '<span class="text-danger">No Relation</span>';
                    }

                    if ($row->trackable instanceof Procurement) {
                        return '<div class="fw-bold text-primary">PO: ' . $row->trackable->po_number . '</div>
                                <small class="text-muted">' . ($row->trackable->vendor->name ?? '-') . '</small>';
                    }

                    if ($row->trackable instanceof InventoryDispatch) {
                        return '<div class="fw-bold text-info">Dispatch: ' . $row->trackable->barcode . '</div>
                                <small class="text-muted">' . ($row->trackable->inventory->name ?? '-') . '</small>';
                    }

                    return '-';
                })

                // 🔥 ORIGIN → DESTINATION
                ->addColumn('origin_dest', function($row){
                    return '<div class="small">' . ($row->origin ?? '-') . '</div>
                            <div class="text-center text-muted small">↓</div>
                            <div class="small">' . ($row->destination ?? '-') . '</div>';
                })

                ->rawColumns(['action', 'status_badge', 'related_to', 'origin_dest'])
                ->make(true);
        }

        return view('logistics_shipments.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(LogisticsShipment $logisticsShipment)
{
    return view('logistics_shipments.edit', compact('logisticsShipment'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogisticsShipment $logisticsShipment)
    {
        $request->validate([
            'status' => 'required|in:pending,in_transit,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'carrier' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'estimated_arrival' => 'nullable|date',
            'actual_arrival' => 'nullable|date',
        ]);

        $logisticsShipment->update($request->all());

        return redirect()
            ->route('logistics-shipments.index')
            ->with('success', 'Shipment updated successfully.');
    }
}