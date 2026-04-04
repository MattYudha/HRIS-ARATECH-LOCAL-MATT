<?php $__env->startSection('content'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css')); ?>">
<?php $__env->stopPush(); ?>



<div class="page-heading">

    <!-- PAGE TITLE -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>New Department</h3>
                <p class="text-subtitle text-muted">Create a new department</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('departments.index')); ?>">Departments</a>
                        </li>
                        <li class="breadcrumb-item active">New</li>
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

                        <!-- ALERT -->
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('departments.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                Department Name <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control"
                                value="<?php echo e(old('name')); ?>"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select
                                name="status"
                                id="status"
                                class="form-select"
                                required
                            >
                                <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                        </div>

                        
                        <div class="col-md-6 mb-3">
                            <label for="manager_id" class="form-label">Manager</label>
                            <select name="manager_id" id="manager_id" class="form-select">
                                <option value="">-- Select Manager --</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->id); ?>" <?php echo e(old('manager_id') == $employee->id ? 'selected' : ''); ?>>
                                        <?php echo e($employee->fullname); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="col-md-6 mb-3">
                            <label for="parent_id" class="form-label">Parent Department</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">-- Select Parent --</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dept->id); ?>" <?php echo e(old('parent_id') == $dept->id ? 'selected' : ''); ?>>
                                        <?php echo e($dept->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="3"
                            ><?php echo e(old('description')); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo e(route('departments.index')); ?>" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Department
                        </button>
                    </div>

                </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('mazer/assets/extensions/choices.js/public/assets/scripts/choices.min.js')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Choices for Manager and Parent Department
        const managerChoices = new Choices('#manager_id', {
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: '-- Select Manager --'
        });

        const parentChoices = new Choices('#parent_id', {
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: '-- Select Parent --'
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/departments/create.blade.php ENDPATH**/ ?>