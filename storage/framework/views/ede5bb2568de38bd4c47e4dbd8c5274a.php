<?php $__env->startSection('content'); ?>



<div class="page-heading">

    <!-- PAGE TITLE -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Departments</h3>
                <p class="text-subtitle text-muted">Manage department data</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Departments</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <section class="section">
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- ACTION -->
                        <div class="d-flex justify-content-end mb-3 gap-2">
                            <a href="<?php echo e(route('departments.org-chart')); ?>" class="btn btn-info text-white">
                                <i class="bi bi-diagram-3"></i> Org Chart
                            </a>
                            <a href="<?php echo e(route('departments.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Department
                            </a>
                        </div>

                        <!-- ALERT -->
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo e(session('error')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="departments-table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Manager / Unit Head</th>
                                        <th class="text-center">Employees</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(function() {
        $('#departments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('departments.index')); ?>",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'manager_name', name: 'manager.fullname', defaultContent: '-' },
                { data: 'employees_count', name: 'employees_count', className: 'text-center', searchable: false },
                { data: 'status', name: 'status', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        // delete confirmation standard
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus departemen ini?');
        });
    });
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/departments/index.blade.php ENDPATH**/ ?>