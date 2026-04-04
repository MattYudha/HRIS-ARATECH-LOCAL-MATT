<?php $__env->startSection('content'); ?>



<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('inventory-usage-logs.index')); ?>">Inventory Usage Logs</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Usage Log</li>
        </ol>
    </nav>

    <h3>Edit Usage Log</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('inventory-usage-logs.update', $log)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row g-3">
                                
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Item <span class="text-danger">*</span>
                                    </label>
                                    <select name="inventory_id" class="form-select" required>
                                        <option value="">Select Item</option>
                                        <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($inventory->id); ?>"
                                                <?php echo e(old('inventory_id', $log->inventory_id) == $inventory->id ? 'selected' : ''); ?>>
                                                <?php echo e($inventory->name); ?> (<?php echo e($inventory->category->name ?? '-'); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Employee <span class="text-danger">*</span>
                                    </label>
                                    <select name="employee_id" class="form-select" required>
                                        <option value="">Select Employee</option>
                                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($employee->id); ?>"
                                                <?php echo e(old('employee_id', $log->employee_id) == $employee->id ? 'selected' : ''); ?>>
                                                <?php echo e($employee->fullname); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Borrowed Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local"
                                           name="borrowed_date"
                                           class="form-control"
                                           value="<?php echo e(old('borrowed_date', $log->borrowed_date->format('Y-m-d\TH:i'))); ?>"
                                           required>
                                </div>

                                
                                <div class="col-md-6">
                                    <label class="form-label">Returned Date</label>
                                    <input type="datetime-local"
                                           name="returned_date"
                                           class="form-control"
                                           value="<?php echo e(old(
                                               'returned_date',
                                               $log->returned_date ? $log->returned_date->format('Y-m-d\TH:i') : ''
                                           )); ?>">
                                </div>

                                
                                <div class="col-12">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes"
                                              rows="4"
                                              class="form-control"
                                              placeholder="Enter notes"><?php echo e(old('notes', $log->notes)); ?></textarea>
                                </div>
                            </div>

                            
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-pencil-square me-1"></i> Update
                                </button>

                                <a href="<?php echo e(route('inventory-usage-logs.index')); ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
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

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventory-usage-logs/edit.blade.php ENDPATH**/ ?>