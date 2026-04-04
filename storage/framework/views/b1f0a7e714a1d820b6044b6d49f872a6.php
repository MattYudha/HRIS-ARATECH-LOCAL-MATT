<?php $__env->startSection('content'); ?>
<div class="page-heading mb-4">
    <h3>Update Shipment</h3>
</div>

<div class="card">
    <div class="card-body">

        <form action="<?php echo e(route('logistics-shipments.update', $logisticsShipment)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="mb-3">
                <label>Related</label>
                <input type="text" class="form-control" readonly
                    value="<?php echo e($logisticsShipment->trackable_type == 'App\Models\Procurement'
                        ? 'PO #' . ($logisticsShipment->trackable?->po_number ?? '-')
                        : 'Inventory Dispatch'); ?>">
            </div>

            
            <div class="mb-3">
                <label>Tracking Number</label>
                <input type="text" name="tracking_number"
                    class="form-control"
                    value="<?php echo e(old('tracking_number', $logisticsShipment->tracking_number)); ?>">
            </div>

            
            <div class="mb-3">
                <label>Carrier</label>
                <input type="text" name="carrier"
                    class="form-control"
                    value="<?php echo e(old('carrier', $logisticsShipment->carrier)); ?>">
            </div>

            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Origin</label>
                    <input type="text" name="origin"
                        class="form-control"
                        value="<?php echo e(old('origin', $logisticsShipment->origin)); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Destination</label>
                    <input type="text" name="destination"
                        class="form-control"
                        value="<?php echo e(old('destination', $logisticsShipment->destination)); ?>">
                </div>
            </div>

            
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="pending" <?php echo e($logisticsShipment->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="in_transit" <?php echo e($logisticsShipment->status == 'in_transit' ? 'selected' : ''); ?>>In Transit</option>
                    <option value="delivered" <?php echo e($logisticsShipment->status == 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                    <option value="cancelled" <?php echo e($logisticsShipment->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
            </div>

            
            <div class="mb-3">
                <label>Estimated Arrival</label>
                <input type="datetime-local" name="estimated_arrival"
                    class="form-control"
                    value="<?php echo e($logisticsShipment->estimated_arrival ? $logisticsShipment->estimated_arrival->format('Y-m-d\TH:i') : ''); ?>">
            </div>

            
            <div class="mb-3">
                <label>Actual Arrival</label>
                <input type="datetime-local" name="actual_arrival"
                    class="form-control"
                    value="<?php echo e($logisticsShipment->actual_arrival ? $logisticsShipment->actual_arrival->format('Y-m-d\TH:i') : ''); ?>">
            </div>

            <button class="btn btn-primary">Update</button>
        </form>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/logistics_shipments/edit.blade.php ENDPATH**/ ?>