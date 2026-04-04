<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Requests</h3>
                <p class="text-subtitle text-muted">Manage leave data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
               <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('leave-requests.index')); ?>">Leave Requests</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            New
        </li>
    </ol>
</nav>

            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">
                
                <?php if(session('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
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

                <?php if(session('role') == 'HR'): ?>
                
                <form action="<?php echo e(route('leave-requests.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select class="form-select <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->id); ?>" 
                                    <?php if(old('employee_id') == $employee->id): ?> selected <?php endif; ?>>
                                    <?php echo e($employee->fullname); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="leave_type" class="form-label">Leave Type</label>
                        <select class="form-control <?php $__errorArgs = ['leave_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="leave_type" required>
                            <option value="" disabled selected>Choose ..</option>
                            <option value="Cuti Tahunan" <?php echo e(old('leave_type') == 'Cuti Tahunan' ? 'selected' : ''); ?>>Cuti Tahunan</option>
                            <option value="Cuti Sakit" <?php echo e(old('leave_type') == 'Cuti Sakit' ? 'selected' : ''); ?>>Cuti Sakit</option>
                            <option value="Cuti Bersama" <?php echo e(old('leave_type') == 'Cuti Bersama' ? 'selected' : ''); ?>>Cuti Bersama</option>
                            <option value="Cuti Melahirkan" <?php echo e(old('leave_type') == 'Cuti Melahirkan' ? 'selected' : ''); ?>>Cuti Melahirkan</option>
                            <option value="Cuti Ayah" <?php echo e(old('leave_type') == 'Cuti Ayah' ? 'selected' : ''); ?>>Cuti Ayah</option>
                            <option value="Cuti Tanpa Gaji" <?php echo e(old('leave_type') == 'Cuti Tanpa Gaji' ? 'selected' : ''); ?>>Cuti Tanpa Gaji</option>
                        </select>
                        <?php $__errorArgs = ['leave_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> datetimeleave" name="start_date" value="<?php echo e(old('start_date')); ?>" required>
                        <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> datetimeleave" name="end_date" value="<?php echo e(old('end_date')); ?>" required>
                        <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo e(route('leave-requests.index')); ?>" class="btn btn-secondary">Back to Leave Requests</a>
                </form>

                <?php else: ?>
                
                <form action="<?php echo e(route('leave-requests.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="leave_type" class="form-label">Leave Type</label>
                        <select class="form-control <?php $__errorArgs = ['leave_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="leave_type" required>
                            <option value="" disabled selected>Choose ..</option>
                            <option value="Cuti Tahunan" <?php echo e(old('leave_type') == 'Cuti Tahunan' ? 'selected' : ''); ?>>Cuti Tahunan</option>
                            <option value="Cuti Sakit" <?php echo e(old('leave_type') == 'Cuti Sakit' ? 'selected' : ''); ?>>Cuti Sakit</option>
                            <option value="Cuti Bersama" <?php echo e(old('leave_type') == 'Cuti Bersama' ? 'selected' : ''); ?>>Cuti Bersama</option>
                            <option value="Cuti Melahirkan" <?php echo e(old('leave_type') == 'Cuti Melahirkan' ? 'selected' : ''); ?>>Cuti Melahirkan</option>
                            <option value="Cuti Ayah" <?php echo e(old('leave_type') == 'Cuti Ayah' ? 'selected' : ''); ?>>Cuti Ayah</option>
                            <option value="Cuti Tanpa Gaji" <?php echo e(old('leave_type') == 'Cuti Tanpa Gaji' ? 'selected' : ''); ?>>Cuti Tanpa Gaji</option>
                        </select>
                        <?php $__errorArgs = ['leave_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> datetimeleave" name="start_date" value="<?php echo e(old('start_date')); ?>" required>
                        <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> datetimeleave" name="end_date" value="<?php echo e(old('end_date')); ?>" required>
                        <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Send</button>
                    <a href="<?php echo e(route('leave-requests.index')); ?>" class="btn btn-secondary">Back to List</a>
                </form>
            
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/leave_requests/create.blade.php ENDPATH**/ ?>