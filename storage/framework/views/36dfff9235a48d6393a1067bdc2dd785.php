<?php $__env->startSection('content'); ?>

<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('inventories.index')); ?>">Inventories</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e($inventory->name); ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center">
        <h3><?php echo e($inventory->name); ?></h3>
        <div>
            <a href="<?php echo e(route('inventories.edit', $inventory)); ?>" class="btn btn-primary me-2" title="Edit">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            <a href="<?php echo e(route('inventories.index')); ?>" class="btn btn-secondary" title="Back">
                <i class="bi bi-arrow-left-circle me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">

        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-muted">Type</small>
                        <h4 class="mt-1">
                            <span class="badge <?php echo e($inventory->item_type == 'habis_pakai' ? 'bg-info' : 'bg-primary'); ?>">
                                <?php echo e($inventory->item_type == 'habis_pakai' ? 'Consumable' : 'Asset'); ?>

                            </span>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-muted">Quantity</small>
                        <h4 class="mt-1"><?php echo e($inventory->quantity); ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-muted">Status</small>
                        <h4 class="mt-1">
                            <?php if($inventory->status === 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php elseif($inventory->status === 'inactive'): ?>
                                <span class="badge bg-warning text-dark">Inactive</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Damaged</span>
                            <?php endif; ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-4">
            <div class="card-header">
                <h4>Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="200">Location</th>
                        <td><?php echo e($inventory->location ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Area / Room</th>
                        <td><?php echo e($inventory->area ?? '-'); ?> / <?php echo e($inventory->room ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Purchase Date</th>
                        <td>
                            <?php echo e($inventory->purchase_date
                                ? $inventory->purchase_date->format('d M Y')
                                : 'N/A'); ?>

                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?php echo e($inventory->description ?? 'N/A'); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h4>Usage History</h4>
            </div>
            <div class="card-body">

                <?php if($inventory->usageLogs->isEmpty()): ?>
                    <div class="text-center text-muted py-4">
                        No usage logs yet
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Borrowed Date</th>
                                    <th>Returned Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $inventory->usageLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($log->employee->fullname); ?></td>
                                        <td><?php echo e($log->borrowed_date->format('d M Y H:i')); ?></td>
                                        <td>
                                            <?php if($log->returned_date): ?>
                                                <?php echo e($log->returned_date->format('d M Y H:i')); ?>

                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">
                                                    Currently Borrowed
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($log->notes ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </section>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventories/show.blade.php ENDPATH**/ ?>