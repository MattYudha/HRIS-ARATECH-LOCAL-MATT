<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Presence Statistics</h3>
                <p class="text-subtitle text-muted">View presence statistics and reports.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('presences.index')); ?>">Presences</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Statistics</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <!-- Date Range Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('presences.statistics')); ?>" class="row g-3">
                    <?php if(in_array(session('role'), ['HR', 'Power User'])): ?>
                    <div class="col-md-4">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select class="form-select" id="employee_id" name="employee_id">
                            <option value="">All Employees</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($emp->id); ?>" <?php echo e($selectedEmployeeId == $emp->id ? 'selected' : ''); ?>>
                                    <?php echo e($emp->fullname); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                    <?php else: ?>
                    <div class="col-md-5">
                    <?php endif; ?>
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e($startDate); ?>" required>
                    </div>
                    <?php if(in_array(session('role'), ['HR', 'Power User'])): ?>
                    <div class="col-md-3">
                    <?php else: ?>
                    <div class="col-md-5">
                    <?php endif; ?>
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e($endDate); ?>" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Total Days</h6>
                        <h3><?php echo e($stats['total_days']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Present</h6>
                        <h3 class="text-success"><?php echo e($stats['present']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Absent</h6>
                        <h3 class="text-danger"><?php echo e($stats['absent']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Leave</h6>
                        <h3 class="text-info"><?php echo e($stats['leave']); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Late Check-ins</h6>
                        <h3 class="text-warning"><?php echo e($stats['late_checkins']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Average Hours</h6>
                        <h3><?php echo e($stats['average_hours'] ? number_format($stats['average_hours'], 2) : 'N/A'); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Work Type Breakdown</h6>
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="badge bg-primary">WFO</span>
                                <h5 class="mt-2"><?php echo e($stats['work_type_breakdown']['WFO']); ?></h5>
                            </div>
                            <div>
                                <span class="badge bg-secondary">WFH</span>
                                <h5 class="mt-2"><?php echo e($stats['work_type_breakdown']['WFH']); ?></h5>
                            </div>
                            <div>
                                <span class="badge bg-info">WFA</span>
                                <h5 class="mt-2"><?php echo e($stats['work_type_breakdown']['WFA']); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="card mt-3">
            <div class="card-body">
                <h5>Attendance Rate</h5>
                <?php
                    $attendanceRate = $stats['total_days'] > 0 
                        ? ($stats['present'] / $stats['total_days']) * 100 
                        : 0;
                ?>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: <?php echo e($attendanceRate); ?>%">
                        <?php echo e(number_format($attendanceRate, 1)); ?>%
                    </div>
                </div>
                <small class="text-muted">Based on total days: <?php echo e($stats['total_days']); ?></small>
            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/presences/statistics.blade.php ENDPATH**/ ?>