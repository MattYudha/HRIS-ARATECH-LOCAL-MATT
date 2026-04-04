<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Inventory Usage Detail</h3>
        <a href="<?php echo e(route('inventory-usage-logs.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Employee:</strong>
                    <p class="text-muted"><?php echo e($log->employee->fullname); ?> (<?php echo e($log->employee->department->name ?? '-'); ?>)</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Inventory Item:</strong>
                    <p class="text-muted"><?php echo e($log->inventory->item_name); ?> (<?php echo e($log->inventory->item_code); ?>)</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Borrowed Date:</strong>
                    <p class="text-muted"><?php echo e($log->borrowed_date ? \Carbon\Carbon::parse($log->borrowed_date)->format('d M Y H:i') : '-'); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Returned Date:</strong>
                    <p class="text-muted">
                        <?php if($log->returned_date): ?>
                            <?php echo e(\Carbon\Carbon::parse($log->returned_date)->format('d M Y H:i')); ?>

                        <?php else: ?>
                            <span class="badge bg-warning">Not Returned</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-12 mb-3">
                    <strong>Notes:</strong>
                    <div class="p-3 bg-light rounded">
                        <?php echo e($log->notes ?: 'No notes provided.'); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <?php if(in_array(session('role'), ['HR Administrator', 'Super Admin', 'Super Admin'])): ?>
            <a href="<?php echo e(route('inventory-usage-logs.edit', $log->id)); ?>" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form action="<?php echo e(route('inventory-usage-logs.destroy', $log->id)); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this log?')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventory-usage-logs/show.blade.php ENDPATH**/ ?>