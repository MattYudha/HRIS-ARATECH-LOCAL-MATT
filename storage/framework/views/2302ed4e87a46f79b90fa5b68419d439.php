

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <h3>Team Performance Overview</h3>
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
                    Showing KPI performance for <strong><?php echo e(count($teamMembers)); ?> team members</strong> in <strong><?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y')); ?></strong>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <h6 class="text-primary font-weight-bold mb-1">Total Team Members</h6>
                        <h2 class="mb-0"><?php echo e(count($teamMembers)); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <h6 class="text-success font-weight-bold mb-1">Excellent Performers</h6>
                        <h2 class="mb-0"><?php echo e($teamKPIs->where('performance_level', 'excellent')->count()); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <h6 class="text-info font-weight-bold mb-1">Good Performers</h6>
                        <h2 class="mb-0"><?php echo e($teamKPIs->where('performance_level', 'good')->count()); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <h6 class="text-warning font-weight-bold mb-1">Avg Team Score</h6>
                        <h2 class="mb-0"><?php echo e(round($teamKPIs->avg('composite_score'), 2)); ?>/100</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Performance Table -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Team Member Performance Rankings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Rank</th>
                                        <th>Employee Name</th>
                                        <th style="width: 120px;">Position</th>
                                        <th style="width: 150px;">Composite Score</th>
                                        <th style="width: 150px;">Performance Level</th>
                                        <th style="width: 200px;">Trend</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $teamKPIs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <?php if($index === 0): ?>
                                                <span class="badge bg-warning"><i class="bi bi-trophy"></i> Top</span>
                                            <?php elseif($index === count($teamKPIs) - 1): ?>
                                                <span class="badge bg-info"><?php echo e($index + 1); ?></span>
                                            <?php else: ?>
                                                <span><?php echo e($index + 1); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e($kpi['employee']->fullname); ?></strong>
                                            <br><small class="text-muted">ID: <?php echo e($kpi['employee']->employee_id ?? 'N/A'); ?></small>
                                        </td>
                                        <td><?php echo e($kpi['employee']->role?->title ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge badge-<?php echo e($kpi['composite_score'] >= 90 ? 'success' : 
                                                    ($kpi['composite_score'] >= 75 ? 'info' : 
                                                    ($kpi['composite_score'] >= 60 ? 'warning' : 'danger'))); ?>">
                                                    <?php echo e(round($kpi['composite_score'], 2)); ?>

                                                </span>
                                                <div class="progress" style="width: 100px; height: 20px;">
                                                    <div class="progress-bar <?php echo e($kpi['composite_score'] >= 90 ? 'bg-success' : 
                                                        ($kpi['composite_score'] >= 75 ? 'bg-info' : 
                                                        ($kpi['composite_score'] >= 60 ? 'bg-warning' : 'bg-danger'))); ?>" style="width: <?php echo e(min($kpi['composite_score'], 100)); ?>%;"></div>
                                                </div>
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
                                            <div class="spark-chart" style="width: 100%; height: 30px;">
                                                <canvas id="chart-<?php echo e($kpi['employee']->id); ?>" style="max-height: 30px;"></canvas>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('kpi.show', $kpi['employee']->id)); ?>?period=<?php echo e($period); ?>" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-2"></i><br>No team members found
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

        <!-- Performance Distribution -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Performance Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php
                                $excellent = $teamKPIs->where('performance_level', 'excellent')->count();
                                $good = $teamKPIs->where('performance_level', 'good')->count();
                                $satisfactory = $teamKPIs->where('performance_level', 'satisfactory')->count();
                                $needsImprovement = $teamKPIs->where('performance_level', 'needs_improvement')->count();
                                $unsatisfactory = $teamKPIs->where('performance_level', 'unsatisfactory')->count();
                                $total = $excellent + $good + $satisfactory + $needsImprovement + $unsatisfactory;
                            ?>
                            
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-star-fill text-success"></i> Excellent</span>
                                    <span><?php echo e($excellent); ?>/<?php echo e($total); ?> (<?php echo e($total > 0 ? round(($excellent/$total)*100, 1) : 0); ?>%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo e($total > 0 ? ($excellent/$total)*100 : 0); ?>%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-hand-thumbs-up-fill text-info"></i> Good</span>
                                    <span><?php echo e($good); ?>/<?php echo e($total); ?> (<?php echo e($total > 0 ? round(($good/$total)*100, 1) : 0); ?>%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: <?php echo e($total > 0 ? ($good/$total)*100 : 0); ?>%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-check-circle-fill text-warning"></i> Satisfactory</span>
                                    <span><?php echo e($satisfactory); ?>/<?php echo e($total); ?> (<?php echo e($total > 0 ? round(($satisfactory/$total)*100, 1) : 0); ?>%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: <?php echo e($total > 0 ? ($satisfactory/$total)*100 : 0); ?>%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-arrow-up-circle-fill text-orange"></i> Needs Improvement</span>
                                    <span><?php echo e($needsImprovement); ?>/<?php echo e($total); ?> (<?php echo e($total > 0 ? round(($needsImprovement/$total)*100, 1) : 0); ?>%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-orange" style="width: <?php echo e($total > 0 ? ($needsImprovement/$total)*100 : 0); ?>%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-x-circle-fill text-danger"></i> Unsatisfactory</span>
                                    <span><?php echo e($unsatisfactory); ?>/<?php echo e($total); ?> (<?php echo e($total > 0 ? round(($unsatisfactory/$total)*100, 1) : 0); ?>%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: <?php echo e($total > 0 ? ($unsatisfactory/$total)*100 : 0); ?>%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Team Score Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item">
                                <span class="text-muted">Highest Score:</span>
                                <strong class="float-end text-success"><?php echo e(round($teamKPIs->max('composite_score'), 2)); ?>/100</strong>
                            </div>
                            <div class="list-group-item">
                                <span class="text-muted">Lowest Score:</span>
                                <strong class="float-end text-danger"><?php echo e(round($teamKPIs->min('composite_score'), 2)); ?>/100</strong>
                            </div>
                            <div class="list-group-item">
                                <span class="text-muted">Average Score:</span>
                                <strong class="float-end text-info"><?php echo e(round($teamKPIs->avg('composite_score'), 2)); ?>/100</strong>
                            </div>
                            <div class="list-group-item">
                                <span class="text-muted">Median Score:</span>
                                <?php
                                    $scores = $teamKPIs->pluck('composite_score')->sort()->values();
                                    $count = $scores->count();
                                    $median = 0;
                                    if ($count > 0) {
                                        $median = $count % 2 === 0 
                                            ? ($scores[$count/2 - 1] + $scores[$count/2]) / 2 
                                            : $scores[floor($count/2)];
                                    }
                                ?>
                                <strong class="float-end"><?php echo e(round($median, 2)); ?>/100</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="<?php echo e(route('reports.export-csv')); ?>?period=<?php echo e($period); ?>" class="btn btn-outline-success">
                    <i class="bi bi-download"></i> Export Team Data to CSV
                </a>
                <a href="<?php echo e(route('reports.monthly-recap')); ?>?period=<?php echo e($period); ?>" class="btn btn-outline-info">
                    <i class="bi bi-file-earmark-text"></i> View Monthly Recap
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function changePeriod() {
        const period = document.getElementById('periodSelect').value;
        window.location.href = `<?php echo e(route('kpi.team')); ?>?period=${period}`;
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/kpi/team.blade.php ENDPATH**/ ?>