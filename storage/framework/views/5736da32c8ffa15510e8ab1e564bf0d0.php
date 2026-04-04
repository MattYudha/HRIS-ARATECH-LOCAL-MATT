

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <h3>Department Performance Analysis</h3>
    <p class="text-muted"><?php echo e(Auth::user()->employee->department->name); ?></p>
</div>

<div class="page-content">
    <div class="container-fluid">
        <!-- Period Selector -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label">Select Period</label>
                <div class="input-group">
                    <input type="month" id="periodSelect" class="form-control" value="<?php echo e($period); ?>" onchange="changePeriod()">
                </div>
            </div>
            <div class="col-md-9">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Department summary for <strong><?php echo e(count($deptEmployees)); ?> employees</strong> in <strong><?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y')); ?></strong>
                </div>
            </div>
        </div>

        <!-- Department Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <h6 class="text-primary font-weight-bold mb-1">Total Employees</h6>
                        <h2 class="mb-0"><?php echo e(count($deptEmployees)); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <h6 class="text-success font-weight-bold mb-1">Department Average</h6>
                        <h2 class="mb-0"><?php echo e(round($avgScore, 2)); ?>/100</h2>
                        <small class="text-muted">Overall Performance</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <h6 class="text-info font-weight-bold mb-1">Highest Performer</h6>
                        <?php
                            $highest = $deptKPIs->sortByDesc('composite_score')->first();
                        ?>
                        <h4 class="mb-0"><?php echo e(round($highest['composite_score'] ?? 0, 2)); ?></h4>
                        <small class="text-muted"><?php echo e($highest['employee']->fullname ?? 'N/A'); ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <h6 class="text-warning font-weight-bold mb-1">Performance Trend</h6>
                        <h4 class="mb-0">
                            <?php
                                $excellent = collect($deptKPIs)->where('performance_level', 'excellent')->count();
                                if($excellent > 0) {
                                    echo '<i class="bi bi-arrow-up text-success"></i> +' . $excellent;
                                } else {
                                    echo '<i class="bi bi-dash text-muted"></i> Stable';
                                }
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Performance Table -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Employee Performance Rankings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">Rank</th>
                                        <th>Employee</th>
                                        <th style="width: 120px;">Position</th>
                                        <th style="width: 150px;">Composite Score</th>
                                        <th style="width: 140px;">Performance Level</th>
                                        <th style="width: 200px;">Score Distribution</th>
                                        <th style="width: 100px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $deptKPIs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <?php if($index === 0): ?>
                                                <span class="badge bg-warning"><i class="bi bi-trophy"></i></span>
                                            <?php else: ?>
                                                <strong><?php echo e($index + 1); ?></strong>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e($kpi['employee']->fullname); ?></strong>
                                            <br><small class="text-muted"><?php echo e($kpi['employee']->employee_id ?? 'N/A'); ?></small>
                                        </td>
                                        <td><?php echo e($kpi['employee']->role?->title ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge badge-<?php echo e($kpi['composite_score'] >= 90 ? 'success' : 
                                                    ($kpi['composite_score'] >= 75 ? 'info' : 
                                                    ($kpi['composite_score'] >= 60 ? 'warning' : 'danger'))); ?>">
                                                    <?php echo e(round($kpi['composite_score'], 2)); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php switch($kpi['performance_level']):
                                                case ('excellent'): ?>
                                                    <span class="badge bg-success">Excellent</span>
                                                    <?php break; ?>
                                                <?php case ('good'): ?>
                                                    <span class="badge bg-info">Good</span>
                                                    <?php break; ?>
                                                <?php case ('satisfactory'): ?>
                                                    <span class="badge bg-warning">Satisfactory</span>
                                                    <?php break; ?>
                                                <?php case ('needs_improvement'): ?>
                                                    <span class="badge bg-warning">Needs Improvement</span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge bg-danger">Unsatisfactory</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar <?php echo e($kpi['composite_score'] >= 90 ? 'bg-success' : 
                                                    ($kpi['composite_score'] >= 75 ? 'bg-info' : 
                                                    ($kpi['composite_score'] >= 60 ? 'bg-warning' : 'bg-danger'))); ?>" style="width: <?php echo e(min($kpi['composite_score'], 100)); ?>%;">
                                                    <?php echo e(round($kpi['composite_score'], 1)); ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('kpi.show', $kpi['employee']->id)); ?>?period=<?php echo e($period); ?>" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-2"></i><br>No employees in this department
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Distribution & Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Performance Distribution</h5>
                    </div>
                    <div class="card-body">
                        <?php
                            $excellent = collect($deptKPIs)->where('performance_level', 'excellent')->count();
                            $good = collect($deptKPIs)->where('performance_level', 'good')->count();
                            $satisfactory = collect($deptKPIs)->where('performance_level', 'satisfactory')->count();
                            $needsImprovement = collect($deptKPIs)->where('performance_level', 'needs_improvement')->count();
                            $unsatisfactory = collect($deptKPIs)->where('performance_level', 'unsatisfactory')->count();
                            $total = count($deptKPIs);
                        ?>
                        
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-success me-2"><?php echo e($excellent); ?></span>
                                        <span>Excellent (90-100)</span>
                                    </div>
                                    <span class="text-muted"><?php echo e(round(($excellent/$total)*100, 1)); ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo e(($excellent/$total)*100); ?>%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-info me-2"><?php echo e($good); ?></span>
                                        <span>Good (75-89)</span>
                                    </div>
                                    <span class="text-muted"><?php echo e(round(($good/$total)*100, 1)); ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: <?php echo e(($good/$total)*100); ?>%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-warning me-2"><?php echo e($satisfactory); ?></span>
                                        <span>Satisfactory (60-74)</span>
                                    </div>
                                    <span class="text-muted"><?php echo e(round(($satisfactory/$total)*100, 1)); ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: <?php echo e(($satisfactory/$total)*100); ?>%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-warning me-2"><?php echo e($needsImprovement); ?></span>
                                        <span>Needs Improvement (45-59)</span>
                                    </div>
                                    <span class="text-muted"><?php echo e(round(($needsImprovement/$total)*100, 1)); ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: <?php echo e(($needsImprovement/$total)*100); ?>%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-danger me-2"><?php echo e($unsatisfactory); ?></span>
                                        <span>Unsatisfactory (&lt;45)</span>
                                    </div>
                                    <span class="text-muted"><?php echo e(round(($unsatisfactory/$total)*100, 1)); ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: <?php echo e(($unsatisfactory/$total)*100); ?>%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Department Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <span class="text-muted">Average Score:</span>
                                <strong class="float-end"><?php echo e(round($avgScore, 2)); ?>/100</strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Highest Score:</span>
                                <strong class="float-end text-success">
                                    <?php echo e(round(collect($deptKPIs)->max('composite_score'), 2)); ?>/100
                                </strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Lowest Score:</span>
                                <strong class="float-end text-danger">
                                    <?php echo e(round(collect($deptKPIs)->min('composite_score'), 2)); ?>/100
                                </strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Standard Deviation:</span>
                                <?php
                                    $scores = collect($deptKPIs)->pluck('composite_score');
                                    $mean = $scores->avg();
                                    $variance = $scores->map(fn($x) => pow($x - $mean, 2))->avg();
                                    $stdDev = sqrt($variance);
                                ?>
                                <strong class="float-end"><?php echo e(round($stdDev, 2)); ?></strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Total Employees:</span>
                                <strong class="float-end"><?php echo e(count($deptEmployees)); ?></strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Employees Above Average:</span>
                                <strong class="float-end text-success">
                                    <?php echo e(collect($deptKPIs)->where('composite_score', '>', $avgScore)->count()); ?>

                                </strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Employees Below Average:</span>
                                <strong class="float-end text-danger">
                                    <?php echo e(collect($deptKPIs)->where('composite_score', '<', $avgScore)->count()); ?>

                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="<?php echo e(route('reports.export-csv')); ?>?period=<?php echo e($period); ?>" class="btn btn-outline-success">
                    <i class="bi bi-download"></i> Export Department Data
                </a>
                <a href="<?php echo e(route('kpi.dashboard')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function changePeriod() {
        const period = document.getElementById('periodSelect').value;
        window.location.href = `<?php echo e(route('kpi.department')); ?>?period=${period}`;
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/kpi/department.blade.php ENDPATH**/ ?>