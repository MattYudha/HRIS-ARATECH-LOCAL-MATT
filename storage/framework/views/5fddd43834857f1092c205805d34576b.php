<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Create Employee</h3>
                <p class="text-subtitle text-muted">Add new employee data</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('employees.index')); ?>">Employees</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">
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

                        <form action="<?php echo e(route('employees.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <!-- LEFT -->
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">NIK (Nomor Induk Karyawan)</label>
                                                <input type="text" name="nik"
                                                    class="form-control"
                                                    value="<?php echo e(old('nik')); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">NPWP</label>
                                                <input type="text" name="npwp"
                                                    class="form-control"
                                                    value="<?php echo e(old('npwp')); ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Fullname</label>
                                        <input type="text" name="fullname"
                                            class="form-control"
                                            value="<?php echo e(old('fullname')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status Karyawan</label>
                                        <select name="employee_status" class="form-select" required>
                                            <?php $__currentLoopData = \App\Models\Employee::getAvailableStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>" <?php echo e(old('employee_status') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email"
                                            class="form-control"
                                            value="<?php echo e(old('email')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone_number"
                                            class="form-control"
                                            value="<?php echo e(old('phone_number')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address"
                                            class="form-control"
                                            value="<?php echo e(old('address')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Place of Birth</label>
                                        <input type="text" name="place_of_birth"
                                            class="form-control"
                                            value="<?php echo e(old('place_of_birth')); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Birth Date</label>
                                        <input type="date" name="birth_date"
                                            class="form-control"
                                            value="<?php echo e(old('birth_date')); ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Gender</label>
                                                <select name="gender" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>Male</option>
                                                    <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Religion</label>
                                                <input type="text" name="religion" class="form-control" value="<?php echo e(old('religion')); ?>" placeholder="e.g. Islam">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Marital Status</label>
                                                <select name="marital_status" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    <option value="single" <?php echo e(old('marital_status') == 'single' ? 'selected' : ''); ?>>Single</option>
                                                    <option value="married" <?php echo e(old('marital_status') == 'married' ? 'selected' : ''); ?>>Married</option>
                                                    <option value="divorced" <?php echo e(old('marital_status') == 'divorced' ? 'selected' : ''); ?>>Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">User Password</label>
                                        <input type="password" name="password"
                                            class="form-control"
                                            placeholder="Min. 8 characters" required>
                                        <small class="text-muted">This password will be used for the employee's login account.</small>
                                    </div>
                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hire Date</label>
                                        <input type="date" name="hire_date"
                                            class="form-control"
                                            value="<?php echo e(old('hire_date')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <select name="department_id" id="department_id" class="form-select" required>
                                            <option value="">-- Select Department --</option>
                                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($department->id); ?>"
                                                    data-manager-id="<?php echo e($department->manager_id); ?>"
                                                    <?php echo e(old('department_id') == $department->id ? 'selected' : ''); ?>>
                                                    <?php echo e($department->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Office Location</label>
                                        <select name="office_location_id" class="form-select" required>
                                            <option value="">-- Select Office Location --</option>
                                            <?php $__currentLoopData = $officeLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $officeLocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($officeLocation->id); ?>"
                                                    <?php echo e(old('office_location_id') == $officeLocation->id ? 'selected' : ''); ?>>
                                                    <?php echo e($officeLocation->name); ?> (<?php echo e($officeLocation->type_label); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <small class="text-muted">Lokasi kerja ini dipakai untuk pengaturan WFO dan presensi.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role_id" class="form-select" required>
                                            <option value="">-- Select Role --</option>
                                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($role->id); ?>"
                                                    <?php echo e(old('role_id') == $role->id ? 'selected' : ''); ?>>
                                                    <?php echo e($role->title); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Resign Date</label>
                                                <input type="date" name="resign_date" class="form-control" value="<?php echo e(old('resign_date')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Permanent Date</label>
                                                <input type="date" name="permanent_date" class="form-control" value="<?php echo e(old('permanent_date')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Contract Expiry</label>
                                                <input type="date" name="contract_expiry" class="form-control" value="<?php echo e(old('contract_expiry')); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Supervisor (for KPI & Approvals)</label>
                                        <select name="supervisor_id" id="supervisor_id" class="form-select">
                                            <option value="">-- Select Supervisor --</option>
                                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($emp->id); ?>"
                                                    <?php echo e(old('supervisor_id') == $emp->id ? 'selected' : ''); ?>>
                                                    <?php echo e($emp->fullname); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <small class="text-muted">KPI and approval requests will be sent to this person.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>
                                                Active
                                            </option>
                                            <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Salary</label>
                                        <input type="number" name="salary"
                                            class="form-control"
                                            value="<?php echo e(old('salary')); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Employee
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const supervisorSelect = document.getElementById('supervisor_id');

    departmentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const managerId = selectedOption.getAttribute('data-manager-id');

        if (managerId) {
            // Auto-select the manager in the supervisor dropdown
            supervisorSelect.value = managerId;
        } else {
            // Optional: reset or leave as is if no manager assigned to department
            // supervisorSelect.value = "";
        }
    });

    // Trigger on load if there's already a selection (e.g., from old input)
    if (departmentSelect.value) {
        const selectedOption = departmentSelect.options[departmentSelect.selectedIndex];
        const managerId = selectedOption.getAttribute('data-manager-id');
        if (managerId && !supervisorSelect.value) {
            supervisorSelect.value = managerId;
        }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/employees/create.blade.php ENDPATH**/ ?>