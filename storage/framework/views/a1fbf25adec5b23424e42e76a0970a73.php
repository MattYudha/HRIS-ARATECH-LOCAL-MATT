<?php $__env->startSection('content'); ?>

<?php
    $isAdmin = in_array(session('role'), ['HR Administrator', 'Super Admin']);
?>

<style>
    .profile-header {
        background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .profile-avatar i {
        font-size: 4rem;
        color: #9ca3af;
    }

    .profile-info h3 {
        color: #facc15;
        margin-bottom: 0.25rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .profile-info .emp-code {
        color: #facc15;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-info .position {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 1rem 1.5rem;
    }

    .nav-tabs-custom .nav-link.active {
        color: #1e3a8a;
        border-bottom-color: #facc15;
        background: transparent;
    }

    .info-table th {
        width: 250px;
        font-weight: 500;
        color: #4b5563;
        border: none;
        padding: 0.75rem 0;
    }

    .info-table td {
        border: none;
        padding: 0.75rem 0;
        color: #1f2937;
    }
</style>

<div class="page-heading">
    <div class="profile-header shadow-sm">
        <div class="profile-avatar">
            <?php if($employee->profile_photo): ?>
                <img src="<?php echo e(asset('storage/' . $employee->profile_photo)); ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <i class="bi bi-person-fill"></i>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <div class="emp-code"><?php echo e($employee->emp_code ?? 'N/A'); ?></div>
            <h3><?php echo e($employee->fullname); ?></h3>
            <div class="position">
                <?php echo e($employee->department->name ?? 'N/A'); ?> & <?php echo e($employee->role->title ?? 'N/A'); ?>

            </div>
        </div>
            <div class="ms-auto align-self-start">
                <a href="<?php echo e(route('employees.edit', $employee->id)); ?>" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Edit Profile
                </a>
            </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-tabs-custom border-bottom" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="working-tab" data-bs-toggle="tab" data-bs-target="#working" type="button" role="tab" aria-controls="working" aria-selected="true">Working Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="false">Personal Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button" role="tab" aria-controls="education" aria-selected="false">Education</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab" aria-controls="family" aria-selected="false">Family Relation</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="career-tab" data-bs-toggle="tab" data-bs-target="#career" type="button" role="tab" aria-controls="career" aria-selected="false">Career History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab" aria-controls="bank" aria-selected="false">Bank Account</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="training-tab" data-bs-toggle="tab" data-bs-target="#training" type="button" role="tab" aria-controls="training" aria-selected="false">Training History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">Documents</button>
                    </li>
                </ul>

                <div class="tab-content p-4" id="profileTabsContent">
                    <!-- Working Information -->
                    <div class="tab-pane fade show active" id="working" role="tabpanel" aria-labelledby="working-tab">
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>Join Date</th>
                                    <td><?php echo e($employee->hire_date ? $employee->hire_date->format('d/m/Y') : '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Resign Date</th>
                                    <td><?php echo e($employee->resign_date ? \Carbon\Carbon::parse($employee->resign_date)->format('d/m/Y') : '-'); ?></td>
                                </tr>
                                 <tr>
                                     <th>Permanent Date</th>
                                     <td><?php echo e($employee->permanent_date ? $employee->permanent_date->format('d/m/Y') : '-'); ?></td>
                                 </tr>
                                 <tr>
                                     <th>Contract Expiry</th>
                                     <td><?php echo e($employee->contract_expiry ? $employee->contract_expiry->format('d/m/Y') : '-'); ?></td>
                                 </tr>
                                <tr>
                                    <th>Work Location</th>
                                    <td><?php echo e($employee->department->name ?? 'Head Office'); ?></td>
                                </tr>
                                <tr>
                                    <th>Homebase</th>
                                    <td>Head Office</td>
                                </tr>
                                 <?php if($isAdmin): ?>
                                 <tr>
                                     <th>JG / PG</th>
                                     <td>
                                         <?php
                                             $latestPos = $employee->employeePositions->sortByDesc('start_date')->first();
                                         ?>
                                         <?php echo e($latestPos->pay_grade_id ?? '-'); ?>

                                     </td>
                                 </tr>
                                 <?php endif; ?>
                                <tr>
                                    <th>Employment Status</th>
                                    <td><?php echo e(ucfirst($employee->employee_status ?? 'Permanent')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Personal Information -->
                    <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>NIK</th>
                                    <td><?php echo e($employee->nik ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <td><?php echo e($employee->fullname); ?></td>
                                </tr>
                                <tr>
                                    <th>Place / Date of Birth</th>
                                    <td><?php echo e($employee->place_of_birth ?? '-'); ?> / <?php echo e($employee->birth_date ? $employee->birth_date->format('d M Y') : '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td><?php echo e(ucfirst($employee->gender ?? '-')); ?></td>
                                </tr>
                                <tr>
                                    <th>Religion</th>
                                    <td><?php echo e($employee->religion ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Marital Status</th>
                                    <td><?php echo e($employee->marital_status ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo e($employee->email); ?></td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td><?php echo e($employee->phone_number ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo e($employee->address ?? '-'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Education -->
                    <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>Latest Education Level</th>
                                    <td><?php echo e($employee->educationLevel->level ?? '-'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Family Relation -->
                    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Relation</th>
                                        <th>NIK</th>
                                        <th>Date of Birth</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $employee->families; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($family->fullname); ?></td>
                                            <td><?php echo e($family->relation); ?></td>
                                            <td><?php echo e($family->nik); ?></td>
                                            <td><?php echo e($family->date_of_birth ? \Carbon\Carbon::parse($family->date_of_birth)->format('d/m/Y') : '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No family data available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Career History -->
                    <div class="tab-pane fade" id="career" role="tabpanel" aria-labelledby="career-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Old Position</th>
                                        <th>New Position</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $employee->mutations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mutation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($mutation->mutation_date->format('d/m/Y')); ?></td>
                                            <td><?php echo e(ucfirst($mutation->type)); ?></td>
                                            <td><?php echo e($mutation->oldDepartment->name ?? '-'); ?> - <?php echo e($mutation->oldRole->title ?? '-'); ?></td>
                                            <td><?php echo e($mutation->newDepartment->name ?? '-'); ?> - <?php echo e($mutation->newRole->title ?? '-'); ?></td>
                                            <td><?php echo e($mutation->reason); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No career history available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bank Account -->
                    <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Bank Name</th>
                                        <th>Account No</th>
                                        <th>Account Holder</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $employee->bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($bank->bank_name); ?></td>
                                            <td><?php echo e($bank->account_no); ?></td>
                                            <td><?php echo e($bank->account_holder); ?></td>
                                            <td>
                                                <?php if($bank->is_primary): ?>
                                                    <span class="badge bg-primary">Primary</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Secondary</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No bank account information available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Training History -->
                    <div class="tab-pane fade" id="training" role="tabpanel" aria-labelledby="training-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Training Name</th>
                                        <th>Provider</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-journal-check fs-2 d-block mb-2"></i>
                                            Training history data is not yet available for this employee.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>ID Number</th>
                                        <th>Description</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $employee->documentIdentities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($doc->identityType->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($doc->identity_number); ?></td>
                                            <td><?php echo e($doc->description ?? '-'); ?></td>
                                            <td>
                                                <?php if($doc->file_name): ?>
                                                    <a href="<?php echo e(asset('storage/' . $doc->file_name)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download"></i> View
                                                    </a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No documents available.</td>
                                        </tr>
                                    <?php endif; ?>
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

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/profile/my-profile.blade.php ENDPATH**/ ?>