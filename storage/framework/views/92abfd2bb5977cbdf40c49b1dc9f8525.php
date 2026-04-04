<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Department Details</h3>
                <p class="text-subtitle text-muted">Detailed information for <?php echo e($department->name); ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('departments.index')); ?>">Departments</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e($department->name); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Department Info -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">General Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="font-bold">Department Name</label>
                            <p class="text-muted"><?php echo e($department->name); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Status</label>
                            <div>
                                <span class="badge <?php echo e($department->status == 'active' ? 'bg-success' : 'bg-secondary'); ?>">
                                    <?php echo e(ucfirst($department->status)); ?>

                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Manager</label>
                            <p class="text-muted">
                                <?php if($department->manager): ?>
                                    <a href="<?php echo e(route('employees.show', $department->manager->id)); ?>">
                                        <?php echo e($department->manager->fullname); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted italic">No manager assigned</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Parent Department</label>
                            <p class="text-muted">
                                <?php if($department->parent): ?>
                                    <a href="<?php echo e(route('departments.show', $department->parent->id)); ?>">
                                        <?php echo e($department->parent->name); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted italic">None</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Description</label>
                            <p class="text-muted"><?php echo e($department->description ?: 'No description provided.'); ?></p>
                        </div>
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('departments.edit', $department->id)); ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="<?php echo e(route('departments.index')); ?>" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Hierarchy & Employees -->
            <div class="col-md-8">
                <!-- Sub-departments -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Sub-Departments</h4>
                    </div>
                    <div class="card-body">
                        <?php if($department->children->count() > 0): ?>
                            <div class="list-group">
                                <?php $__currentLoopData = $department->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('departments.show', $child->id)); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <?php echo e($child->name); ?>

                                        <span class="badge bg-primary rounded-pill"><?php echo e($child->employees->count()); ?> Employees</span>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted italic">No sub-departments found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Employees in this Department -->
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Employees (<?php echo e($department->employees->count()); ?>)</h4>
                    </div>
                    <div class="card-body">
                        <?php if($department->employees->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fullname</th>
                                            <th>Position</th>
                                            <th>Role</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $department->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($emp->fullname); ?></td>
                                                <td><?php echo e($emp->position->name ?? '-'); ?></td>
                                                <td><?php echo e($emp->role->title ?? '-'); ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo e(route('employees.show', $emp->id)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted italic">No employees assigned to this department.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/departments/show.blade.php ENDPATH**/ ?>