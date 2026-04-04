<?php $__env->startPush('styles'); ?>
<style>
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 1rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border-bottom: 2px solid transparent;
    }
    .nav-tabs .nav-link:hover {
        color: #435ebe;
        background: rgba(67, 94, 190, 0.05);
        border-color: transparent;
    }
    .nav-tabs .nav-link.active {
        color: #435ebe;
        background: transparent;
        border-bottom: 2px solid #435ebe;
    }
    .tab-pane {
        padding: 1rem 0.5rem;
    }
    .bg-primary-light { background-color: rgba(67, 94, 190, 0.1); }
    .bg-success-light { background-color: rgba(25, 135, 84, 0.1); }
    .bg-info-light { background-color: rgba(13, 202, 240, 0.1); }
    .bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
    
    .custom-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .word-break-all { word-break: break-all; }
    
    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
        font-weight: 700;
        color: #4b5563;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $role = session('role');
    $isAdmin = in_array($role, ['HR Administrator', 'Super Admin']);
    // Everyone can edit their own personal data sections (Family, Bank, Documents, etc.)
    $canEditFamily = true;
    $canDeleteFamily = true;
    $canEditEducation = true;
?>



<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Edit Employee</h3>
                <p class="text-subtitle text-muted">Update employee data</p>
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
                        <li class="breadcrumb-item active">Edit</li>
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

                        <form action="<?php echo e(route('employees.update', $employee->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4" id="employeeEditTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                                        <i class="bi bi-person me-1"></i> General
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="employment-tab" data-bs-toggle="tab" data-bs-target="#employment" type="button" role="tab" aria-controls="employment" aria-selected="false">
                                        <i class="bi bi-briefcase me-1"></i> Employment
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab" aria-controls="family" aria-selected="false">
                                        <i class="bi bi-people me-1"></i> Family
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance" type="button" role="tab" aria-controls="finance" aria-selected="false">
                                        <i class="bi bi-credit-card me-1"></i> Finance & Docs
                                    </button>
                                </li>
                                <?php if(in_array(session('role'), ['HR Administrator', 'Super Admin']) && $userAccount): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                                        <i class="bi bi-shield-lock me-1"></i> Security
                                    </button>
                                </li>
                                <?php endif; ?>
                            </ul>

                            <div class="tab-content border-0 p-0" id="employeeEditTabContent">
                                <!-- General Tab -->
                                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">NIK (Nomor Induk Karyawan)</label>
                                                        <input type="text" name="nik" class="form-control" value="<?php echo e(old('nik', $employee->nik)); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">NPWP</label>
                                                        <input type="text" name="npwp" class="form-control" value="<?php echo e(old('npwp', $employee->npwp)); ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Fullname</label>
                                                <input type="text" name="fullname" class="form-control" value="<?php echo e(old('fullname', $employee->fullname)); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status Karyawan</label>
                                                <select name="employee_status" class="form-select" required>
                                                    <?php $__currentLoopData = \App\Models\Employee::getAvailableStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>" <?php echo e(old('employee_status', $employee->employee_status) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $employee->email)); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Phone Number</label>
                                                <input type="text" name="phone_number" class="form-control" value="<?php echo e(old('phone_number', $employee->phone_number)); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $employee->address)); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Place of Birth</label>
                                                <input type="text" name="place_of_birth" class="form-control" value="<?php echo e(old('place_of_birth', $employee->place_of_birth)); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Birth Date</label>
                                                <input type="date" name="birth_date" class="form-control" value="<?php echo e(old('birth_date', \Carbon\Carbon::parse($employee->birth_date)->format('Y-m-d'))); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Education Level</label>
                                                <select name="education_level_id" class="form-select" <?php echo e($canEditEducation ? '' : 'disabled'); ?>>
                                                    <option value="">-- Select --</option>
                                                    <?php $__currentLoopData = $educationLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($level->education_level_id); ?>" <?php echo e(old('education_level_id', $employee->education_level_id) == $level->education_level_id ? 'selected' : ''); ?>>
                                                            <?php echo e($level->level); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php if (! ($canEditEducation)): ?>
                                                    <input type="hidden" name="education_level_id" value="<?php echo e($employee->education_level_id); ?>">
                                                <?php endif; ?>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Gender</label>
                                                        <select name="gender" class="form-select">
                                                            <option value="">-- Select --</option>
                                                            <option value="male" <?php echo e(old('gender', $employee->gender) == 'male' ? 'selected' : ''); ?>>Male</option>
                                                            <option value="female" <?php echo e(old('gender', $employee->gender) == 'female' ? 'selected' : ''); ?>>Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Religion</label>
                                                        <input type="text" name="religion" class="form-control" value="<?php echo e(old('religion', $employee->religion)); ?>" placeholder="e.g. Islam">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Marital Status</label>
                                                        <select name="marital_status" class="form-select">
                                                            <option value="">-- Select --</option>
                                                            <option value="single" <?php echo e(old('marital_status', $employee->marital_status) == 'single' ? 'selected' : ''); ?>>Single</option>
                                                            <option value="married" <?php echo e(old('marital_status', $employee->marital_status) == 'married' ? 'selected' : ''); ?>>Married</option>
                                                            <option value="divorced" <?php echo e(old('marital_status', $employee->marital_status) == 'divorced' ? 'selected' : ''); ?>>Divorced</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employment Tab -->
                                <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Hire Date</label>
                                                <input type="date" name="hire_date" id="hire_date" class="form-control <?php $__errorArgs = ['hire_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '')); ?>" <?php echo e($isAdmin ? '' : 'readonly'); ?> required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Department</label>
                                                <select name="department_id" id="department_id" class="form-select <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php echo e($isAdmin ? '' : 'disabled'); ?>>
                                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($department->id); ?>" <?php echo e(old('department_id', $employee->department_id) == $department->id ? 'selected' : ''); ?>>
                                                            <?php echo e($department->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Office Location</label>
                                                <select name="office_location_id" id="office_location_id" class="form-select <?php $__errorArgs = ['office_location_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php echo e($isAdmin ? '' : 'disabled'); ?>>
                                                    <?php $__currentLoopData = $officeLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $officeLocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($officeLocation->id); ?>" <?php echo e(old('office_location_id', $employee->office_location_id) == $officeLocation->id ? 'selected' : ''); ?>>
                                                            <?php echo e($officeLocation->name); ?> (<?php echo e($officeLocation->type_label); ?>)
                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <small class="text-muted">Lokasi kerja ini dipakai untuk pengaturan WFO dan presensi.</small>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Role</label>
                                                <select name="role_id" id="role_id" class="form-select <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php echo e($isAdmin ? '' : 'disabled'); ?>>
                                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($roleItem->id); ?>" <?php echo e(old('role_id', $employee->role_id) == $roleItem->id ? 'selected' : ''); ?>>
                                                            <?php echo e($roleItem->title); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Resign Date</label>
                                                <input type="date" name="resign_date" class="form-control" value="<?php echo e(old('resign_date', $employee->resign_date ? $employee->resign_date->format('Y-m-d') : '')); ?>" <?php echo e($isAdmin ? '' : 'readonly'); ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Permanent Date</label>
                                                <input type="date" name="permanent_date" class="form-control" value="<?php echo e(old('permanent_date', $employee->permanent_date ? $employee->permanent_date->format('Y-m-d') : '')); ?>" <?php echo e($isAdmin ? '' : 'readonly'); ?>>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Contract Expiry</label>
                                                <input type="date" name="contract_expiry" class="form-control" value="<?php echo e(old('contract_expiry', $employee->contract_expiry ? $employee->contract_expiry->format('Y-m-d') : '')); ?>" <?php echo e($isAdmin ? '' : 'readonly'); ?>>
                                            </div>
                                            <?php if(in_array(session('role'), ['HR Administrator', 'Super Admin'])): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Reporting To (Supervisor)</label>
                                                <select name="supervisor_id" class="form-select">
                                                    <option value="">-- No Supervisor --</option>
                                                    <?php $__currentLoopData = $potentialSupervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($supervisor->id); ?>" <?php echo e(old('supervisor_id', $employee->supervisor_id) == $supervisor->id ? 'selected' : ''); ?>>
                                                            <?php echo e($supervisor->fullname); ?> (<?php echo e($supervisor->role->title ?? 'N/A'); ?>)
                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <?php endif; ?>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="active" <?php echo e(old('status', $employee->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                                                    <option value="inactive" <?php echo e(old('status', $employee->status) == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                                </select>
                                            </div>
                                            <?php if($isAdmin): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Salary</label>
                                                <input type="number" name="salary" id="salary" class="form-control <?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('salary', $employee->salary)); ?>" step="0.01">
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <div class="mb-3">
                                                <label class="form-label">Mutation/Promotion Reason (Optional)</label>
                                                <textarea name="mutation_reason" class="form-control" rows="2"><?php echo e(old('mutation_reason')); ?></textarea>
                                                <small class="text-muted">Recorded only if department, role, or salary changes.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Family Tab -->
                                <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Family Members</h6>
                                        <?php if($canEditFamily): ?>
                                            <button id="add-family" class="btn btn-sm btn-outline-primary" type="button"><i class="bi bi-plus-circle"></i> Add Family</button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle border">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Relation</th>
                                                    <th>NIK</th>
                                                    <th>No KK</th>
                                                    <th>Tempat Lahir</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Gender</th>
                                                    <?php if($canDeleteFamily): ?>
                                                        <th style="width: 50px;" class="text-center">Aksi</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody id="family-rows">
                                                <?php $__currentLoopData = $families; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $fam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="family-row" data-family-id="<?php echo e($fam->id); ?>">
                                                        <td>
                                                            <input type="hidden" name="families[<?php echo e($index); ?>][id]" value="<?php echo e($fam->id); ?>">
                                                            <input type="text" class="form-control form-control-sm" name="families[<?php echo e($index); ?>][fullname]" value="<?php echo e($fam->fullname); ?>" <?php echo e($canEditFamily ? '' : 'readonly'); ?>>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm" name="families[<?php echo e($index); ?>][relation]" value="<?php echo e($fam->relation); ?>" <?php echo e($canEditFamily ? '' : 'readonly'); ?>></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="families[<?php echo e($index); ?>][nik]" value="<?php echo e($fam->nik); ?>" <?php echo e($canEditFamily ? '' : 'readonly'); ?>></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="families[<?php echo e($index); ?>][no_kk]" value="<?php echo e($fam->no_kk); ?>" <?php echo e($canEditFamily ? '' : 'readonly'); ?>></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="families[<?php echo e($index); ?>][place_of_birth]" value="<?php echo e($fam->place_of_birth); ?>" <?php echo e($canEditFamily ? '' : 'readonly'); ?>></td>
                                                        <td><input type="date" class="form-control form-control-sm" name="families[<?php echo e($index); ?>][date_of_birth]" value="<?php echo e(optional($fam->date_of_birth)->format('Y-m-d')); ?>" <?php echo e($canEditFamily ? '' : 'readonly'); ?>></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="families[<?php echo e($index); ?>][gender]" value="<?php echo e($fam->gender); ?>" <?php echo e($canEditFamily ? '' : 'readonly'); ?>></td>
                                                        <?php if($canDeleteFamily): ?>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteFamilyRow(this)"><i class="bi bi-trash"></i></button>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php if($canDeleteFamily): ?>
                                        <div id="families-to-delete"></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Finance Tab -->
                                <div class="tab-pane fade" id="finance" role="tabpanel" aria-labelledby="finance-tab">
                                    <div class="mb-3 d-flex justify-content-between align-items-center mt-2">
                                        <h6 class="mb-0">Bank Accounts</h6>
                                        <button id="add-bank" class="btn btn-sm btn-outline-primary" type="button"><i class="bi bi-plus-circle"></i> Add Bank</button>
                                    </div>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-sm align-middle border">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Bank Name</th>
                                                    <th>Account No</th>
                                                    <th>Holder</th>
                                                    <th class="text-center">Primary</th>
                                                    <th style="width: 50px;" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bank-rows">
                                                <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="bank-row" data-bank-id="<?php echo e($bank->id); ?>">
                                                        <td>
                                                            <input type="hidden" name="banks[<?php echo e($index); ?>][id]" value="<?php echo e($bank->id); ?>">
                                                            <input type="text" class="form-control form-control-sm" name="banks[<?php echo e($index); ?>][bank_name]" value="<?php echo e($bank->bank_name); ?>">
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm" name="banks[<?php echo e($index); ?>][account_no]" value="<?php echo e($bank->account_no); ?>"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="banks[<?php echo e($index); ?>][account_holder]" value="<?php echo e($bank->account_holder); ?>"></td>
                                                        <td class="text-center">
                                                            <input class="form-check-input bank-primary-radio" type="radio" name="bank_primary_index" value="<?php echo e($index); ?>" <?php echo e($bank->is_primary ? 'checked' : ''); ?>>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBankRow(this)"><i class="bi bi-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="banks-to-delete"></div>

                                    <div class="mb-3 d-flex justify-content-between align-items-center mt-2">
                                        <h6 class="mb-0">Document Identities</h6>
                                        <button id="add-document" class="btn btn-sm btn-outline-primary" type="button"><i class="bi bi-plus-circle"></i> Add Document</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle border">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Type</th>
                                                    <th>ID Number</th>
                                                    <th>Description</th>
                                                    <th>File</th>
                                                    <th style="width: 100px;" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="document-rows">
                                                <?php $__currentLoopData = $documentIdentities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="document-row" data-document-id="<?php echo e($doc->id); ?>">
                                                        <td style="width: 150px;">
                                                            <input type="hidden" name="documents[<?php echo e($index); ?>][id]" value="<?php echo e($doc->id); ?>">
                                                            <select name="documents[<?php echo e($index); ?>][identity_type_id]" class="form-select form-select-sm">
                                                                <?php $__currentLoopData = $identityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($type->identity_type_id); ?>" <?php echo e($doc->identity_type_id == $type->identity_type_id ? 'selected' : ''); ?>>
                                                                        <?php echo e($type->name); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm" name="documents[<?php echo e($index); ?>][identity_number]" value="<?php echo e($doc->identity_number); ?>"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="documents[<?php echo e($index); ?>][description]" value="<?php echo e($doc->description); ?>"></td>
                                                        <td>
                                                            <?php if($doc->file_name): ?>
                                                                <a href="<?php echo e(asset('storage/employee_documents/' . $employee->id . '/' . $doc->file_name)); ?>" target="_blank" class="btn btn-xs btn-outline-info">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="text-muted small">No File</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button" class="btn btn-outline-primary" onclick="showUploadModal(<?php echo e($doc->id); ?>, '<?php echo e($doc->identityType->name ?? 'Document'); ?>')">
                                                                    <i class="bi bi-upload"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger" onclick="deleteDocumentRow(this)">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="documents-to-delete"></div>
                                </div>

                                <!-- Security Tab -->
                                <?php if(in_array(session('role'), ['HR Administrator', 'Super Admin']) && $userAccount): ?>
                                <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                    <div class="alert alert-light-info border-0 rounded-3 mb-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-info-circle-fill fs-4 text-info me-3"></i>
                                            <div>
                                                <h6 class="mb-1 text-info">Device Registration Info</h6>
                                                <p class="mb-0 small">Karyawan hanya dapat melakukan absen dari perangkat yang telah didaftarkan. Anda dapat merest pendaftaran jika mereka berganti perangkat.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border shadow-none mb-4">
                                        <div class="card-header bg-light-danger border-bottom p-3">
                                            <h6 class="mb-0 text-danger"><i class="bi bi-key-fill me-2"></i> Password Reset</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <p class="text-muted small mb-3">Kosongkan jika tidak ingin mengubah password karyawan ini. Minimal 8 karakter.</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Confirm New Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <div class="card border h-100 shadow-none">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary-light p-2 rounded-3 me-3">
                                                                <i class="bi bi-laptop text-primary fs-4"></i>
                                                            </div>
                                                            <h6 class="mb-0">Desktop / Laptop</h6>
                                                        </div>
                                                        <?php if($userAccount->browser_fingerprint_desktop): ?>
                                                            <span class="badge bg-success-light text-success"><i class="bi bi-check-circle-fill me-1"></i> Registered</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-light text-muted">Not Registered</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <?php if($userAccount->browser_fingerprint_desktop): ?>
                                                        <div class="bg-light p-2 rounded mb-3">
                                                            <code class="text-muted small word-break-all"><?php echo e($userAccount->browser_fingerprint_desktop); ?></code>
                                                        </div>
                                                        <div class="form-check form-switch custom-switch">
                                                            <input class="form-check-input" type="checkbox" name="reset_desktop_fingerprint" id="reset_desktop">
                                                            <label class="form-check-label text-danger small pt-1" for="reset_desktop">Reset Registration ID</label>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border h-100 shadow-none">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary-light p-2 rounded-3 me-3">
                                                                <i class="bi bi-phone text-primary fs-4"></i>
                                                            </div>
                                                            <h6 class="mb-0">Mobile Device</h6>
                                                        </div>
                                                        <?php if($userAccount->browser_fingerprint_mobile): ?>
                                                            <span class="badge bg-success-light text-success"><i class="bi bi-check-circle-fill me-1"></i> Registered</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-light text-muted">Not Registered</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <?php if($userAccount->browser_fingerprint_mobile): ?>
                                                        <div class="bg-light p-2 rounded mb-3">
                                                            <code class="text-muted small word-break-all"><?php echo e($userAccount->browser_fingerprint_mobile); ?></code>
                                                        </div>
                                                        <div class="form-check form-switch custom-switch">
                                                            <input class="form-check-input" type="checkbox" name="reset_mobile_fingerprint" id="reset_mobile">
                                                            <label class="form-check-label text-danger small pt-1" for="reset_mobile">Reset Registration ID</label>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <hr class="mt-4">

                            <div class="d-flex justify-content-between mt-4">
                                <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </a>
                                <?php if($isAdmin): ?>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-2"></i> Update Data
                                </button>
                                <?php else: ?>
                                <div class="text-end">
                                    <p class="text-muted small mb-2"><i class="bi bi-info-circle me-1"></i> Perubahan Anda akan diajukan ke HR Administrator untuk disetujui.</p>
                                    <button type="submit" class="btn btn-warning px-4">
                                        <i class="bi bi-send me-2"></i> Submit for Approval
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php if($canEditFamily): ?>
    <!-- Document Upload Modal -->
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="uploadDocumentForm" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadDocumentModalLabel">Upload Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Document Type</label>
                            <input type="text" id="modal_doc_type" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Identity Number</label>
                            <input type="text" name="identity_number" id="modal_id_number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Identity Type</label>
                            <select name="identity_type_id" id="modal_id_type_id" class="form-select" required>
                                <?php $__currentLoopData = $identityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->identity_type_id); ?>"><?php echo e($type->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select File</label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea name="description" id="modal_description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload & Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
<script>
    const familyRows = document.getElementById('family-rows');
    const bankRows = document.getElementById('bank-rows');
    const documentRows = document.getElementById('document-rows');
    const canDeleteFamily = <?php echo e($canDeleteFamily ? 'true' : 'false'); ?>;

    // Document Upload Logic
    const uploadModal = new bootstrap.Modal(document.getElementById('uploadDocumentModal'));
    const uploadForm = document.getElementById('uploadDocumentForm');

    window.showUploadModal = function(docId, typeName) {
        uploadForm.action = "<?php echo e(route('employees.documents.store', $employee->id)); ?>";
        document.getElementById('modal_doc_type').value = typeName;
        
        if (docId) {
            // Find row data
            const row = document.querySelector(`.document-row[data-document-id="${docId}"]`);
            if (row) {
                document.getElementById('modal_id_number').value = row.querySelector('input[name*="identity_number"]').value;
                document.getElementById('modal_id_type_id').value = row.querySelector('select[name*="identity_type_id"]').value;
                document.getElementById('modal_description').value = row.querySelector('input[name*="description"]').value;
            }
        } else {
            document.getElementById('modal_id_number').value = '';
            document.getElementById('modal_description').value = '';
        }
        
        uploadModal.show();
    }

    // Family Logic
    // ... (rest of the scripts)

    // Family Logic
    document.getElementById('add-family')?.addEventListener('click', function (e) {
        e.preventDefault();
        const index = familyRows.querySelectorAll('.family-row').length + Date.now();
        const rowHtml = `
        <tr class="family-row">
            <td><input type="text" class="form-control form-control-sm" name="families[${index}][fullname]"></td>
            <td><input type="text" class="form-control form-control-sm" name="families[${index}][relation]"></td>
            <td><input type="text" class="form-control form-control-sm" name="families[${index}][nik]"></td>
            <td><input type="text" class="form-control form-control-sm" name="families[${index}][no_kk]"></td>
            <td><input type="text" class="form-control form-control-sm" name="families[${index}][place_of_birth]"></td>
            <td><input type="date" class="form-control form-control-sm" name="families[${index}][date_of_birth]"></td>
            <td><input type="text" class="form-control form-control-sm" name="families[${index}][gender]"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteFamilyRow(this)"><i class="bi bi-trash"></i></button></td>
        </tr>`;
        familyRows.insertAdjacentHTML('beforeend', rowHtml);
    });

    window.deleteFamilyRow = function(btn) {
        const row = btn.closest('.family-row');
        const existingId = row?.dataset.familyId;
        if (existingId) {
            const target = document.getElementById('families-to-delete');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'families_to_delete[]';
            hidden.value = existingId;
            target.appendChild(hidden);
        }
        row.remove();
    }

    // Bank Logic
    document.getElementById('add-bank')?.addEventListener('click', function (e) {
        e.preventDefault();
        const index = bankRows.querySelectorAll('.bank-row').length + Date.now();
        const rowHtml = `
        <tr class="bank-row">
            <td><input type="text" class="form-control form-control-sm" name="banks[${index}][bank_name]"></td>
            <td><input type="text" class="form-control form-control-sm" name="banks[${index}][account_no]"></td>
            <td><input type="text" class="form-control form-control-sm" name="banks[${index}][account_holder]"></td>
            <td class="text-center">
                <div class="form-check d-flex justify-content-center">
                    <input class="form-check-input bank-primary-radio" type="radio" name="bank_primary_index" value="${index}">
                </div>
            </td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBankRow(this)"><i class="bi bi-trash"></i></button></td>
        </tr>`;
        bankRows.insertAdjacentHTML('beforeend', rowHtml);
    });

    window.deleteBankRow = function(btn) {
        const row = btn.closest('.bank-row');
        const existingId = row?.dataset.bankId;
        if (existingId) {
            const target = document.getElementById('banks-to-delete');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'banks_to_delete[]';
            hidden.value = existingId;
            target.appendChild(hidden);
        }
        row.remove();
    }

    // Document Logic
    document.getElementById('add-document')?.addEventListener('click', function (e) {
        e.preventDefault();
        showUploadModal(null, 'New Document');
    });

    window.deleteDocumentRow = function(btn) {
        const row = btn.closest('.document-row');
        const existingId = row?.dataset.documentId;
        if (existingId) {
            const target = document.getElementById('documents-to-delete');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'documents_to_delete[]';
            hidden.value = existingId;
            target.appendChild(hidden);
        }
        row.remove();
    }
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/employees/edit.blade.php ENDPATH**/ ?>