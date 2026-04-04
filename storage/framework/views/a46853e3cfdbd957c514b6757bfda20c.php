<?php $__env->startSection('content'); ?>

<?php
    $isPowerUser = session('role') === 'Super Admin';
?>



<div class="page-heading">

    <!-- PAGE TITLE -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Roles</h3>
                <p class="text-subtitle text-muted">
                    Manage roles data
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Roles</li>
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <?php if (! ($isPowerUser)): ?>
                                    <span class="badge bg-secondary">View only - Super Admin required to edit</span>
                                <?php endif; ?>
                            </div>
                            <?php if($isPowerUser): ?>
                                <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> New Role
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- ALERT -->
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table1">
                                <thead>
                                    <tr>
                                        <th style="width: 22%">Title</th>
                                        <th>Description</th>
                                        <th class="text-center" style="width: 200px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fw-semibold">
                                                <?php echo e($role->title); ?>

                                            </td>
                                            <td>
                                                <?php echo e($role->description ?? '-'); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php if($isPowerUser): ?>
                                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                        <!-- EDIT INFO -->
                                                        <a
                                                            href="<?php echo e(route('roles.edit', $role->id)); ?>"
                                                            class="btn btn-sm btn-light-warning"
                                                            data-bs-toggle="tooltip"
                                                            title="Edit Role"
                                                        >
                                                            <i class="bi bi-pencil"></i>
                                                        </a>

                                                        <!-- EDIT AKSES -->
                                                        <a
                                                            href="<?php echo e(route('roles.edit', $role->id)); ?>#akses"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="tooltip"
                                                            title="Edit akses module untuk role ini"
                                                        >
                                                            Edit Akses
                                                        </a>

                                                        <!-- DELETE -->
                                                        <form
                                                            action="<?php echo e(route('roles.destroy', $role->id)); ?>"
                                                            method="POST"
                                                            class="delete-form"
                                                        >
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-light-danger"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Role"
                                                            >
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Hanya Super Admin yang bisa edit</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    $(document).ready(function() {
        $('#table1').DataTable();

        // delete confirmation standard
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus role ini?');
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/roles/index.blade.php ENDPATH**/ ?>