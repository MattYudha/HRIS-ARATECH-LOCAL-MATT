

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>KPI Report - <?php echo e($employee->fullname); ?></h3>
            <p class="text-muted"><?php echo e($employee->department->name); ?> • <?php echo e($employee->role?->title); ?></p>
        </div>
        <div>
            <a href="<?php echo e(route('kpi.trend', $employee->id)); ?>" class="btn btn-sm btn-outline-success">
                <i class="bi bi-graph-up"></i> View Trend
            </a>
            <a href="<?php echo e(route('reports.export-pdf', $employee->id)); ?>?period=<?php echo e($period); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container-fluid">
        <!-- Period and Summary -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-muted mb-2">Period</h6>
                                <div class="input-group input-group-sm">
                                    <input type="month" id="periodSelect" class="form-control" value="<?php echo e($period); ?>" onchange="changePeriod()">
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6 class="text-muted mb-2">Composite Score</h6>
                                <h2 class="mb-0">
                                    <span class="text-<?php echo e(($kpiRecords->first()?->composite_score ?? 0) >= 90 ? 'success' : 
                                        (($kpiRecords->first()?->composite_score ?? 0) >= 75 ? 'info' : 
                                        (($kpiRecords->first()?->composite_score ?? 0) >= 60 ? 'warning' : 'danger'))); ?>">
                                        <?php echo e(round($kpiRecords->first()?->composite_score ?? 0, 2)); ?>/100
                                    </span>
                                </h2>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6 class="text-muted mb-2">Performance Level</h6>
                                <span class="badge badge-<?php echo e($kpiRecords->first()?->performance_level === 'excellent' ? 'success' : 
                                    ($kpiRecords->first()?->performance_level === 'good' ? 'info' : 
                                    ($kpiRecords->first()?->performance_level === 'satisfactory' ? 'warning' : 
                                    ($kpiRecords->first()?->performance_level === 'needs_improvement' ? 'warning' : 'danger')))); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $kpiRecords->first()?->performance_level ?? 'N/A'))); ?>

                                </span>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6 class="text-muted mb-2">KPIs Achieved</h6>
                                <h4 class="mb-0"><?php echo e($kpiRecords->where('status', 'achieved')->count()); ?>/<?php echo e($kpiRecords->count()); ?></h4>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6 class="text-muted mb-2">Warnings</h6>
                                <h4 class="mb-0 text-warning"><?php echo e($kpiRecords->where('status', 'warning')->count()); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Categories -->
        <?php $__currentLoopData = $kpisByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $records): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $avgScore = $records->avg(function($r) { return $r->getAchievementPercentage(); });
            // Determine color based on average
            $cardBorderColor = $avgScore >= 90 ? 'success' : ($avgScore >= 75 ? 'info' : ($avgScore >= 60 ? 'warning' : 'danger'));
        ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-left-<?php echo e($cardBorderColor); ?> shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 text-dark fw-bold">
                            <?php echo e($category); ?>

                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-2 small text-uppercase fw-bold">Average Achievement</span>
                            <span class="badge bg-<?php echo e($cardBorderColor); ?> rounded-pill px-3 py-2">
                                <?php echo e(round($avgScore, 1)); ?>%
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Metric</th>
                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7" style="width: 100px;">Target</th>
                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7" style="width: 100px;">Actual</th>
                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7" style="width: 200px;">Achievement</th>
                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7" style="width: 120px;">Status</th>
                                        <th class="text-end px-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7" style="width: 100px;">Variance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex flex-column">
                                                <span class="mb-1 text-dark fw-bold"><?php echo e($record->kpi->name); ?></span>
                                                <span class="text-muted small"><?php echo e($record->kpi->unit); ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-dark font-weight-bold"><?php echo e($record->target_value); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary font-weight-bold"><?php echo e($record->actual_value); ?></span>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                                $achievement = $record->getAchievementPercentage();
                                            ?>
                                            <div class="d-flex align-items-center px-2">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px; border-radius: 4px; background-color: #e9ecef;">
                                                    <div class="progress-bar <?php echo e($achievement >= 100 ? 'bg-success' : ($achievement >= 80 ? 'bg-warning' : 'bg-danger')); ?>" 
                                                         role="progressbar"
                                                         style="width: <?php echo e(min($achievement, 100)); ?>%; border-radius: 4px;">
                                                    </div>
                                                </div>
                                                <span class="small fw-bold <?php echo e($achievement >= 100 ? 'text-success' : ($achievement >= 80 ? 'text-warning' : 'text-danger')); ?>">
                                                    <?php echo e(round($achievement, 1)); ?>%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php switch($record->status):
                                                case ('achieved'): ?>
                                                    <span class="badge bg-light-success text-success fw-bold px-3 py-2 rounded-pill border border-success border-opacity-25">Achieved</span>
                                                    <?php break; ?>
                                                <?php case ('warning'): ?>
                                                    <span class="badge bg-light-warning text-warning fw-bold px-3 py-2 rounded-pill border border-warning border-opacity-25">Warning</span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge bg-light-danger text-danger fw-bold px-3 py-2 rounded-pill border border-danger border-opacity-25">Critical</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td class="text-end px-4">
                                            <?php
                                                $variance = $record->getVariance();
                                            ?>
                                            <span class="fw-bold <?php echo e($variance > 0 ? 'text-success' : 'text-danger'); ?>">
                                                <?php echo e($variance > 0 ? '+' : ''); ?><?php echo e(round($variance, 2)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Performance Review -->
        <?php if($performanceReview): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">Performance Review - <?php echo e($performanceReview->reviewed_by); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Strengths</h6>
                                <p><?php echo e($performanceReview->strengths ?? 'No strengths recorded'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Areas for Improvement</h6>
                                <p><?php echo e($performanceReview->areas_for_improvement ?? 'No improvement areas recorded'); ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="mb-3">Comments</h6>
                                <p><?php echo e($performanceReview->comments ?? 'No comments'); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Reviewed Date: <?php echo e($performanceReview->reviewed_date->format('d M Y')); ?></small>
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="badge bg-<?php echo e($performanceReview->status === 'approved' ? 'success' : 'warning'); ?>">
                                    <?php echo e(ucfirst($performanceReview->status)); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No performance review available for this period.
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
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
        window.location.href = `<?php echo e(route('kpi.show', $employee->id)); ?>?period=${period}`;
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/kpi/show.blade.php ENDPATH**/ ?>