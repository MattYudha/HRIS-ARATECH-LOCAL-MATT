
<!-- Overall Performance Trend Chart -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up"></i> Overall Performance Trend
                </h5>
            </div>
            <div class="card-body">
                <div style="height: 300px; min-height: 300px; position: relative; width: 100%; overflow: hidden;">
                    <canvas id="overallTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Breakdown Chart -->
<?php if(!empty($categories)): ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart"></i> Category Performance Breakdown
                </h5>
            </div>
            <div class="card-body">
                <div style="height: 300px; min-height: 300px; position: relative; width: 100%; overflow: hidden;">
                    <canvas id="categoryTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Performance Summary Table -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table"></i> Monthly Performance Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Period</th>
                                <th>Composite Score</th>
                                <th>Performance Level</th>
                                <th>Trend</th>
                                <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th><?php echo e($cat); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $trendData ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($data['period_label']); ?></strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($data['composite_score'] >= 90 ? 'success' : 
                                        ($data['composite_score'] >= 75 ? 'info' : 
                                        ($data['composite_score'] >= 60 ? 'warning' : 'danger'))); ?>">
                                        <?php echo e(round($data['composite_score'], 1)); ?>/100
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($data['performance_level'] === 'excellent' ? 'success' : 
                                        ($data['performance_level'] === 'good' ? 'info' : 
                                        ($data['performance_level'] === 'satisfactory' ? 'warning' : 'danger'))); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $data['performance_level']))); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($index > 0): ?>
                                        <?php
                                            $prevScore = $trendData[$index - 1]['composite_score'];
                                            $currentScore = $data['composite_score'];
                                            $diff = $currentScore - $prevScore;
                                        ?>
                                        <?php if($diff > 0): ?>
                                            <span class="text-success"><i class="bi bi-arrow-up"></i> +<?php echo e(round($diff, 1)); ?></span>
                                        <?php elseif($diff < 0): ?>
                                            <span class="text-danger"><i class="bi bi-arrow-down"></i> <?php echo e(round($diff, 1)); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="bi bi-dash"></i> 0</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td>
                                    <?php if(isset($data['category_scores'][$cat])): ?>
                                        <?php echo e(round($data['category_scores'][$cat], 1)); ?>

                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Insights & Recommendations -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0"><i class="bi bi-lightbulb"></i> Performance Insights</h5>
            </div>
            <div class="card-body">
                <?php
                    $scores = array_column($trendData ?? [], 'composite_score');
                    $avgScore = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
                    $firstScore = $scores[0] ?? 0;
                    $lastScore = end($scores) ?: 0;
                    $trend = $lastScore - $firstScore;
                ?>
                <div class="row">
                    <div class="col-md-4 text-center border-end">
                        <h6 class="text-muted">Average Score</h6>
                        <h2 class="mb-0"><?php echo e(round($avgScore, 1)); ?></h2>
                        <small class="text-muted">Over <?php echo e($months ?? 6); ?> months</small>
                    </div>
                    <div class="col-md-4 text-center border-end">
                        <h6 class="text-muted">Overall Trend</h6>
                        <h2 class="mb-0 <?php echo e($trend >= 0 ? 'text-success' : 'text-danger'); ?>">
                            <?php echo e($trend >= 0 ? '+' : ''); ?><?php echo e(round($trend, 1)); ?>

                        </h2>
                        <small class="text-muted"><?php echo e($trend >= 0 ? 'Improving' : 'Declining'); ?></small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h6 class="text-muted">Current Status</h6>
                        <h2 class="mb-0">
                            <span class="badge bg-<?php echo e($lastScore >= 90 ? 'success' : 
                                ($lastScore >= 75 ? 'info' : 
                                ($lastScore >= 60 ? 'warning' : 'danger'))); ?>">
                                <?php echo e(round($lastScore, 1)); ?>

                            </span>
                        </h2>
                        <small class="text-muted">Latest month</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof Chart === 'undefined') return;

        // Shared Options to prevent resizing jitter
        const commonOptions = {
            responsive: true, 
            maintainAspectRatio: false,
            resizeDelay: 200, // Debounce resize events
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            }
        };

        // Prepare data from PHP
        const trendData = <?php echo json_encode($trendData ?? []); ?>;
        const categories = <?php echo json_encode($categories ?? []); ?>;

        // Overall Trend Chart
        const ctxOverall = document.getElementById('overallTrendChart');
        if (ctxOverall) {
            new Chart(ctxOverall.getContext('2d'), {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.period_label),
                    datasets: [{
                        label: 'Composite Score',
                        data: trendData.map(d => d.composite_score),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: commonOptions // USe shared options
            });
        }

        // Category Breakdown Chart
        const ctxCategory = document.getElementById('categoryTrendChart');
        if (ctxCategory && categories.length > 0) {
            const categoryColors = {
                'Attendance': 'rgb(54, 162, 235)',
                'Productivity': 'rgb(255, 159, 64)',
                'Quality': 'rgb(153, 102, 255)',
                'Behavior': 'rgb(255, 99, 132)',
                'Department': 'rgb(75, 192, 192)',
                'Compliance': 'rgb(201, 203, 207)'
            };

            const categoryDatasets = categories.map(cat => {
                return {
                    label: cat,
                    data: trendData.map(d => d.category_scores[cat] || 0),
                    borderColor: categoryColors[cat] || 'rgb(150, 150, 150)',
                    backgroundColor: (categoryColors[cat] || 'rgb(150, 150, 150)').replace('rgb', 'rgba').replace(')', ', 0.1)'),
                    tension: 0.4,
                    fill: false
                };
            });

            new Chart(ctxCategory.getContext('2d'), {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.period_label),
                    datasets: categoryDatasets
                },
                options: commonOptions // Use shared options
            });
        }
    });
</script>
<?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/partials/kpi-trend-content.blade.php ENDPATH**/ ?>