<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">System Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">System Management</li>
    </ol>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-database me-1"></i>
                    Backup & Restore
                </div>
                <div class="card-body">
                    <p>Manage your system data backups and restoration.</p>
                    <div class="alert alert-info">
                        <strong>Last Backup:</strong> 2026-04-03 12:00:00 (Placeholder)
                    </div>
                    <form action="<?php echo e(route('system.backup')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download me-1"></i> Trigger New Backup
                        </button>
                    </form>
                    <hr>
                    <button class="btn btn-outline-danger" onclick="alert('Restore logic requires manual intervention for safety.')">
                        <i class="fas fa-upload me-1"></i> Restore from File
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle me-1"></i>
                    System Information
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Environment</th>
                            <td><?php echo e(config('app.env')); ?></td>
                        </tr>
                        <tr>
                            <th>Debug Mode</th>
                            <td><?php echo e(config('app.debug') ? 'ON' : 'OFF'); ?></td>
                        </tr>
                        <tr>
                            <th>PHP Version</th>
                            <td><?php echo e(phpversion()); ?></td>
                        </tr>
                        <tr>
                            <th>Laravel Version</th>
                            <td><?php echo e(app()->version()); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/system/index.blade.php ENDPATH**/ ?>