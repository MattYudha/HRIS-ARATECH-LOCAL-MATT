<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Audit Trail</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Audit Trail</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            System Activity Log
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="auditTable">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Resource</th>
                            <th>Details</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?></td>
                            <td><?php echo e($log->user->name ?? 'System'); ?></td>
                            <td>
                                <span class="badge <?php if($log->event == 'created'): ?> bg-success <?php elseif($log->event == 'updated'): ?> bg-warning <?php else: ?> bg-danger <?php endif; ?>">
                                    <?php echo e(strtoupper($log->event)); ?>

                                </span>
                            </td>
                            <td><?php echo e(class_basename($log->auditable_type)); ?> #<?php echo e($log->auditable_id); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-details" 
                                        data-old='<?php echo json_encode($log->old_values, 15, 512) ?>' 
                                        data-new='<?php echo json_encode($log->new_values, 15, 512) ?>'>
                                    View Diff
                                </button>
                            </td>
                            <td><?php echo e($log->ip_address); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <?php echo e($logs->links()); ?>

            </div>
        </div>
    </div>
</div>

<!-- Modal for Details -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Old Values</h6>
                        <pre id="oldValues" class="bg-light p-3 border rounded"></pre>
                    </div>
                    <div class="col-md-6">
                        <h6>New Values</h6>
                        <pre id="newValues" class="bg-light p-3 border rounded"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    const oldPre = document.getElementById('oldValues');
    const newPre = document.getElementById('newValues');

    document.querySelectorAll('.view-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const oldVals = JSON.parse(this.dataset.old || '{}');
            const newVals = JSON.parse(this.dataset.new || '{}');
            
            oldPre.textContent = JSON.stringify(oldVals, null, 2);
            newPre.textContent = JSON.stringify(newVals, null, 2);
            
            modal.show();
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/audit/index.blade.php ENDPATH**/ ?>