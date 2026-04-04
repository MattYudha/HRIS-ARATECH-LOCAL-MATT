<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Presences</h3>
                <p class="text-subtitle text-muted">Monitor presences data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('presences.index')); ?>">Presences</a>
        </li>
    </ol>
</nav>

            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">

                <div class="d-flex gap-2 mb-3">
                    <a href="<?php echo e(route('presences.create')); ?>" class="btn btn-primary">New Presence</a>
                    <a href="<?php echo e(route('presences.calendar')); ?>" class="btn btn-info">Calendar View</a>
                    <a href="<?php echo e(route('presences.statistics')); ?>" class="btn btn-secondary">Statistics</a>
                    <?php if(in_array(session('role'), ['HR Administrator', 'Super Admin'])): ?>
                        <a href="<?php echo e(route('presences.export')); ?>" class="btn btn-success"><i class="bi bi-download"></i> Export CSV</a>
                    <?php endif; ?>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show">
                        <?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped align-middle nowrap" id="presence-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Work Type</th>
                                <th>Office Site</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(function() {
        $('#presence-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('presences.index')); ?>",
            order: [[1, 'desc']],
            columns: [
                { data: 'employee.fullname', name: 'employee.fullname', defaultContent: '<em>Unknown</em>' },
                { data: 'date', name: 'date' },
                { data: 'check_in', name: 'check_in' },
                { data: 'check_out', name: 'check_out' },
                { data: 'work_type_badge', name: 'work_type', orderable: false, searchable: false },
                { data: 'office_location_name', name: 'office_location_name', orderable: false, searchable: false, defaultContent: '-' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        // delete confirmation standard
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus data presensi ini?');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/presences/index.blade.php ENDPATH**/ ?>