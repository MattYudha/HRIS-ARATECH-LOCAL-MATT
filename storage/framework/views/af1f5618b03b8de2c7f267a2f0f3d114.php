<?php $__env->startSection('content'); ?>
<div class="page-heading mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('procurements.index')); ?>">Procurements</a></li>
            <li class="breadcrumb-item active" aria-current="page">PO #<?php echo e($procurement->po_number); ?></li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h3>Purchase Order: <?php echo e($procurement->po_number); ?></h3>
        <div>
            <?php if($procurement->status === 'pending'): ?>
                <a href="<?php echo e(route('procurements.order', $procurement->id)); ?>" class="btn btn-warning" onclick="return confirm('Mark as Ordered?')">
                    <i class="bi bi-send me-1"></i> Send / Mark as Ordered
                </a>
            <?php endif; ?>
            <?php if($procurement->status === 'ordered'): ?>
                <a href="<?php echo e(route('procurements.receive', $procurement->id)); ?>" class="btn btn-success" onclick="return confirm('Confirm receipt of all items? This will update stock levels.')">
                    <i class="bi bi-check-all me-1"></i> Confirm Received
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Order Summary</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th style="width: 140px;">Status</th>
                            <td>
                                <?php
                                    $class = match($procurement->status) {
                                        'pending' => 'bg-light-secondary text-secondary',
                                        'ordered' => 'bg-light-warning text-warning',
                                        'received' => 'bg-light-success text-success',
                                        'cancelled' => 'bg-light-danger text-danger',
                                        default => 'bg-light-secondary'
                                    };
                                ?>
                                <span class="badge <?php echo e($class); ?>"><?php echo e(ucfirst($procurement->status)); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Vendor</th>
                            <td><?php echo e($procurement->vendor->name); ?></td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td><?php echo e($procurement->order_date->format('d M Y')); ?></td>
                        </tr>
                        <tr>
                            <th>Requested By</th>
                            <td><?php echo e($procurement->requester->name); ?></td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td class="fw-bold">Rp <?php echo e(number_format($procurement->total_amount, 0, ',', '.')); ?></td>
                        </tr>
                    </table>

                    <?php if($procurement->notes): ?>
                        <div class="mt-3">
                            <h6>Notes:</h6>
                            <p class="text-muted"><?php echo e($procurement->notes); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($procurement->shipment): ?>
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h4 class="text-white">Logistics Info</h4>
                    </div>
                    <div class="card-body pt-3">
                        <p><strong>Tracking No:</strong> <?php echo e($procurement->shipment->tracking_number ?? '-'); ?></p>
                        <p><strong>Carrier:</strong> <?php echo e($procurement->shipment->carrier ?? '-'); ?></p>
                        <p><strong>Status:</strong> <span class="badge bg-info"><?php echo e(ucfirst($procurement->shipment->status)); ?></span></p>
                        <a href="<?php echo e(route('logistics-shipments.edit', $procurement->shipment)); ?>" class="btn btn-sm btn-outline-primary w-100">Update Shipment</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Ordered Items</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $procurement->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($item->inventory_id): ?>
                                                <a href="<?php echo e(route('inventories.show', $item->inventory_id)); ?>"><?php echo e($item->item_name); ?></a>
                                            <?php else: ?>
                                                <?php echo e($item->item_name); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?php echo e($item->quantity); ?></td>
                                        <td class="text-end">Rp <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></td>
                                        <td class="text-end fw-bold">Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Grand Total</th>
                                    <th class="text-end text-primary">Rp <?php echo e(number_format($procurement->total_amount, 0, ',', '.')); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if($procurement->status === 'received'): ?>
                        <div class="alert alert-light-success mt-4">
                            <i class="bi bi-check-circle me-1"></i> All items have been received and quantities updated in the inventory system.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/procurements/show.blade.php ENDPATH**/ ?>