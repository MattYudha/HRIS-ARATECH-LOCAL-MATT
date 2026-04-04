<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Employee Detail</h3>
                <p class="text-subtitle text-muted">Detail employee information</p>
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
                        <li class="breadcrumb-item active">Detail</li>
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

                        <div class="row mb-4">
                            <!-- LEFT -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted">NIK (Nomor Induk Karyawan)</label>
                                            <div class="fw-semibold"><?php echo e($employee->nik); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted">NPWP</label>
                                            <div class="fw-semibold"><?php echo e($employee->npwp ?: '-'); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Fullname</label>
                                    <div class="fw-semibold"><?php echo e($employee->fullname); ?></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Status Karyawan</label>
                                    <div><?php echo $employee->employee_status_badge; ?></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Email</label>
                                    <div><?php echo e($employee->email); ?></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Role</label>
                                    <div><?php echo e($employee->role->title); ?></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Department</label>
                                    <div><?php echo e($employee->department->name); ?></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Office Location</label>
                                    <div><?php echo e($employee->officeLocation?->name ?? '-'); ?></div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Status</label>
                                    <div>
                                        <?php if($employee->status === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted">Salary</label>
                                    <div class="fw-semibold">
                                        <?php if(in_array(session('role'), ['HR Administrator', 'Super Admin'])): ?>
                                            Rp <?php echo e(number_format($employee->salary, 0, ',', '.')); ?>

                                        <?php else: ?>
                                            ***
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Present</label>
                                    <div class="fw-semibold text-success">
                                        <?php echo e($present); ?>

                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Leave</label>
                                    <div class="fw-semibold text-warning">
                                        <?php echo e($leave); ?>

                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted">Absence</label>
                                    <div class="fw-semibold text-danger">
                                        <?php echo e($absent); ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Mutation History</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Change History</th>
                                                <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $employee->mutations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mutation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($mutation->mutation_date->format('d M Y')); ?></td>
                                                    <td>
                                                        <?php
                                                            $badgeClass = match($mutation->type) {
                                                                'promotion' => 'bg-success',
                                                                'mutation' => 'bg-info',
                                                                'demotion' => 'bg-danger',
                                                                default => 'bg-secondary'
                                                            };
                                                        ?>
                                                        <span class="badge <?php echo e($badgeClass); ?>"><?php echo e(ucfirst($mutation->type)); ?></span>
                                                    </td>
                                                    <td style="font-size: 0.85rem;">
                                                        <?php if($mutation->old_department_id !== $mutation->new_department_id): ?>
                                                            <div><strong>Dept:</strong> <?php echo e($mutation->oldDepartment->name ?? '-'); ?> <i class="bi bi-arrow-right mx-1"></i> <?php echo e($mutation->newDepartment->name ?? '-'); ?></div>
                                                        <?php endif; ?>
                                                        <?php if($mutation->old_role_id !== $mutation->new_role_id): ?>
                                                            <div><strong>Role:</strong> <?php echo e($mutation->oldRole->title ?? '-'); ?> <i class="bi bi-arrow-right mx-1"></i> <?php echo e($mutation->newRole->title ?? '-'); ?></div>
                                                        <?php endif; ?>
                                                        <?php if((float)$mutation->old_salary !== (float)$mutation->new_salary): ?>
                                                            <?php if(in_array(session('role'), ['HR Administrator', 'Super Admin'])): ?>
                                                                <div><strong>Salary:</strong> Rp <?php echo e(number_format($mutation->old_salary, 0, ',', '.')); ?> <i class="bi bi-arrow-right mx-1"></i> Rp <?php echo e(number_format($mutation->new_salary, 0, ',', '.')); ?></div>
                                                            <?php else: ?>
                                                                <div><strong>Salary:</strong> *** <i class="bi bi-arrow-right mx-1"></i> ***</div>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="font-size: 0.85rem;"><?php echo e($mutation->reason ?: '-'); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-3">No mutation history available.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="bi bi-award me-2"></i>Incidents & Achievements</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Dibuat Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $employee->incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($incident->incident_date->format('d M Y')); ?></td>
                                                    <td>
                                                        <?php
                                                            $typeBadge = match(strtolower($incident->type)) {
                                                                'sp1', 'sp2', 'sp3', 'peringatan' => 'bg-danger',
                                                                'penghargaan', 'award', 'prestasi' => 'bg-success',
                                                                'mutasi', 'promosi' => 'bg-info',
                                                                default => 'bg-secondary'
                                                            };
                                                        ?>
                                                        <span class="badge <?php echo e($typeBadge); ?>"><?php echo e(strtoupper($incident->type)); ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold"><?php echo e($incident->description); ?></div>
                                                        <?php if($incident->action_taken): ?>
                                                            <div class="small text-muted">Aksi: <?php echo e($incident->action_taken); ?></div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $statusClass = match($incident->status) {
                                                                'resolved', 'closed' => 'text-success',
                                                                'investigating' => 'text-warning',
                                                                default => 'text-muted'
                                                            };
                                                        ?>
                                                        <span class="<?php echo e($statusClass); ?> small fw-bold"><?php echo e(ucfirst($incident->status)); ?></span>
                                                    </td>
                                                    <td><?php echo e($incident->reportedBy->name ?? 'System'); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-3">No incidents or awards recorded.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>

                            <?php
                                $role = session('role');
                                $isAdmin = in_array($role, ['HR Administrator', 'Super Admin']);
                                $currentEmployeeId = session('employee_id');
                            ?>

                            <?php if($isAdmin || $employee->id == $currentEmployeeId): ?>
                            <a href="<?php echo e(route('employees.edit', $employee->id)); ?>" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Edit Employee
                            </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/employees/show.blade.php ENDPATH**/ ?>