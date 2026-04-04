@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-4">
    <h3>Update Shipment</h3>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('logistics-shipments.update', $logisticsShipment) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- RELATED --}}
            <div class="mb-3">
                <label>Related</label>
                <input type="text" class="form-control" readonly
                    value="{{ $logisticsShipment->trackable_type == 'App\Models\Procurement'
                        ? 'PO #' . ($logisticsShipment->trackable?->po_number ?? '-')
                        : 'Inventory Dispatch' }}">
            </div>

            {{-- TRACKING --}}
            <div class="mb-3">
                <label>Tracking Number</label>
                <input type="text" name="tracking_number"
                    class="form-control"
                    value="{{ old('tracking_number', $logisticsShipment->tracking_number) }}">
            </div>

            {{-- CARRIER --}}
            <div class="mb-3">
                <label>Carrier</label>
                <input type="text" name="carrier"
                    class="form-control"
                    value="{{ old('carrier', $logisticsShipment->carrier) }}">
            </div>

            {{-- ORIGIN DEST --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Origin</label>
                    <input type="text" name="origin"
                        class="form-control"
                        value="{{ old('origin', $logisticsShipment->origin) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Destination</label>
                    <input type="text" name="destination"
                        class="form-control"
                        value="{{ old('destination', $logisticsShipment->destination) }}">
                </div>
            </div>

            {{-- STATUS --}}
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ $logisticsShipment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_transit" {{ $logisticsShipment->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="delivered" {{ $logisticsShipment->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $logisticsShipment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- ETA --}}
            <div class="mb-3">
                <label>Estimated Arrival</label>
                <input type="datetime-local" name="estimated_arrival"
                    class="form-control"
                    value="{{ $logisticsShipment->estimated_arrival ? $logisticsShipment->estimated_arrival->format('Y-m-d\TH:i') : '' }}">
            </div>

            {{-- ACTUAL --}}
            <div class="mb-3">
                <label>Actual Arrival</label>
                <input type="datetime-local" name="actual_arrival"
                    class="form-control"
                    value="{{ $logisticsShipment->actual_arrival ? $logisticsShipment->actual_arrival->format('Y-m-d\TH:i') : '' }}">
            </div>

            <button class="btn btn-primary">Update</button>
        </form>

    </div>
</div>
@endsection