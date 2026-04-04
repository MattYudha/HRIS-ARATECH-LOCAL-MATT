<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Presence</h3>
                <p class="text-subtitle text-muted">Monitor presences data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Presences</li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <form action="<?php echo e(route('presences.update', $presence->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
        
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" class="form-control" id="employee_id" required>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->id); ?>" <?php echo e($presence->employee_id == $employee->id ? 'selected' : ''); ?>><?php echo e($employee->fullname); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
        
                    <div class="mb-3">
                        <label for="check_in" class="form-label">Check-in Time</label>
                        <input type="datetime-local" name="check_in" class="form-control datetime" id="check_in" value="<?php echo e(old('check_in', $presence->check_in)); ?>" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="check_out" class="form-label">Check-out Time</label>
                        <input type="datetime-local" name="check_out" class="form-control datetime" id="check_out" value="<?php echo e(old('check_out', $presence->check_out ? $presence->check_out : '')); ?>">
                    </div>
        
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control" id="status" required>
                            <option value="present" <?php echo e($presence->status == 'present' ? 'selected' : ''); ?>>Present</option>
                            <option value="absent" <?php echo e($presence->status == 'absent' ? 'selected' : ''); ?>>Absent</option>
                            <option value="leave" <?php echo e($presence->status == 'leave' ? 'selected' : ''); ?>>Leave</option>
                        </select>
                    </div>
        
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/presences/edit.blade.php ENDPATH**/ ?>