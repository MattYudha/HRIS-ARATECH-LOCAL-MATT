<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Performance Trend - <?php echo e($employee->fullname); ?></h3>
            <p class="text-muted"><?php echo e($employee->department->name); ?> • <?php echo e($employee->role?->title); ?></p>
        </div>
        <div>
            <a href="<?php echo e(route('kpi.show', $employee->id)); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to KPI Report
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container-fluid">
        <div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-3">Overview</h4>
        <?php echo $__env->make('partials.dashboard-content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<!-- Filter Controls -->
<div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="monthsSelect" class="form-label">Time Range</label>
                                <select id="monthsSelect" class="form-select" onchange="changeMonths()">
                                    <option value="3" <?php echo e($months == 3 ? 'selected' : ''); ?>>Last 3 Months</option>
                                    <option value="6" <?php echo e($months == 6 ? 'selected' : ''); ?>>Last 6 Months</option>
                                    <option value="9" <?php echo e($months == 9 ? 'selected' : ''); ?>>Last 9 Months</option>
                                    <option value="12" <?php echo e($months == 12 ? 'selected' : ''); ?>>Last 12 Months</option>
                                </select>
                            </div>
                            <div class="col-md-9 text-end">
                                <div class="d-inline-flex gap-2">
                                    <?php $__currentLoopData = ['excellent' => 'success', 'good' => 'info', 'satisfactory' => 'warning', 'needs_improvement' => 'warning', 'unsatisfactory' => 'danger']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-<?php echo e($color); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $level))); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Partial -->
        <?php echo $__env->make('partials.kpi-trend-content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<!-- Chart.js -->
<script src="<?php echo e(asset('vendor/chartjs/chart.umd.min.js')); ?>"></script>

<script>
    function changeMonths() {
        const months = document.getElementById('monthsSelect').value;
        const url = new URL(window.location.href);
        url.searchParams.set('months', months);
        window.location.href = url.toString();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/kpi/trend.blade.php ENDPATH**/ ?>