<?php $__env->startSection('content'); ?>

<?php
    $selectedAccess = old('access', []);
?>



<div class="page-heading">

    <!-- PAGE TITLE -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Create Role</h3>
                <p class="text-subtitle text-muted">
                    Add new role data
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('roles.index')); ?>">Roles</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <section class="section">
        <div class="row">
            <div class="col-12 col-12">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- ALERT -->
                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- FORM -->
                        <form action="<?php echo e(route('roles.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control"
                                    value="<?php echo e(old('title')); ?>"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control"
                                    rows="3"
                                ><?php echo e(old('description')); ?></textarea>
                            </div>

                            <div class="mb-3" id="akses">
                                <label class="form-label fw-semibold">Akses Modul</label>
                                <p class="text-muted small mb-2">Centang modul yang boleh diakses oleh role ini.</p>
                                <div class="row g-2">
                                    <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-6">
                                            <div class="form-check border rounded p-2 h-100">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="access[]"
                                                    value="<?php echo e($module['key']); ?>"
                                                    id="access_<?php echo e($module['key']); ?>"
                                                    <?php echo e(in_array($module['key'], $selectedAccess) ? 'checked' : ''); ?>

                                                >
                                                <label class="form-check-label" for="access_<?php echo e($module['key']); ?>">
                                                    <?php echo e($module['label']); ?>

                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Save Role
                                </button>
                                <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-secondary">
                                    Back
                                </a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/roles/create.blade.php ENDPATH**/ ?>