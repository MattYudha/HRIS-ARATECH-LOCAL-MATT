

<?php if($isGlobal ?? false): ?>

<div class="row g-3 mb-4">
    <?php $__currentLoopData = [
        ['Departments', $departmentCount ?? 0, 'bi-diagram-3', 'bg-purple'],
        ['Employees', $employeeCount ?? 0, 'bi-people', 'bg-blue'],
        ['Presences', $presenceCount ?? 0, 'bi-calendar-check', 'bg-green'],
        ['Payrolls', $payrollCount ?? 0, 'bi-cash-stack', 'bg-red']
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title, $count, $icon, $bg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center px-4 py-4">
                <div>
                    <h6 class="text-muted mb-1"><?php echo e($title); ?></h6>
                    <h3 class="fw-bold mb-0"><?php echo e($count); ?></h3>
                </div>
                <div class="icon-circle <?php echo e($bg); ?>">
                    <i class="bi <?php echo e($icon); ?>"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php else: ?>

<div class="row g-3 mb-4">
    
    <div class="col-12 mb-2">
        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body px-4 py-4 text-white">
                <div class="d-flex align-items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name ?? 'User')); ?>&background=ffffff&color=667eea&size=56"
                         class="rounded-circle" width="56" height="56">
                    <div>
                        <h4 class="mb-1 fw-bold">Selamat datang, <?php echo e(auth()->user()->name ?? 'User'); ?>!</h4>
                        <p class="mb-0 opacity-75" style="font-size: 14px;">
                            <i class="bi bi-shield-check me-1"></i> <?php echo e(session('role', 'Employee')); ?>

                            &nbsp;&bull;&nbsp;
                            <i class="bi bi-calendar3 me-1"></i> <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__currentLoopData = [
        ['Kehadiran Saya', $presenceCount ?? 0, 'bi-calendar-check', 'bg-green'],
        ['Tugas Saya', $myTaskCount ?? 0, 'bi-list-task', 'bg-blue'],
        ['Tugas Pending', $pendingTaskCount ?? 0, 'bi-hourglass-split', 'bg-orange'],
        ['Surat Saya', $myLetterCount ?? 0, 'bi-envelope-paper', 'bg-purple']
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title, $count, $icon, $bg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center px-4 py-4">
                <div>
                    <h6 class="text-muted mb-1"><?php echo e($title); ?></h6>
                    <h3 class="fw-bold mb-0"><?php echo e($count); ?></h3>
                </div>
                <div class="icon-circle <?php echo e($bg); ?>">
                    <i class="bi <?php echo e($icon); ?>"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <?php echo e(($isGlobal ?? false) ? 'Presence Chart' : 'Kehadiran Saya'); ?>

                </h5>
            </div>
            <div class="card-body">
                <div style="height: 300px; min-height: 300px; position: relative; width: 100%; overflow: hidden;">
                    <canvas id="presenceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <?php echo e(($isGlobal ?? false) ? 'Payroll Chart' : 'Payroll Saya'); ?>

                </h5>
            </div>
            <div class="card-body">
                <div style="height: 300px; min-height: 300px; position: relative; width: 100%; overflow: hidden;">
                    <canvas id="payrollChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($isGlobal ?? false): ?>
<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Distribusi Status Karyawan</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div style="height: 300px; position: relative;">
                            <canvas id="employeeStatusChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo e(['#38bdf8', '#10b981', '#fb923c', '#8b5cf6', '#a78bfa'][$index % 5]); ?>"> </span>
                                            <?php echo e($label); ?>

                                        </td>
                                        <td class="text-end fw-bold"><?php echo e($statusData[$index]); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<style>
.icon-circle {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #fff;
}
.bg-purple { background: linear-gradient(135deg, #8b5cf6, #a78bfa); }
.bg-blue { background: linear-gradient(135deg, #38bdf8, #0ea5e9); }
.bg-green { background: linear-gradient(135deg, #34d399, #10b981); }
.bg-red { background: linear-gradient(135deg, #fb7185, #ef4444); }
.bg-orange { background: linear-gradient(135deg, #fb923c, #f97316); }
</style>


<script>
document.addEventListener("DOMContentLoaded", () => {
    if (typeof Chart !== 'undefined') {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            resizeDelay: 200,
            layout: { padding: { top: 10, bottom: 10 } }
        };

        const presenceCtx = document.getElementById('presenceChart');
        if (presenceCtx) {
            new Chart(presenceCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($presenceLabels ?? []); ?>,
                    datasets: [{
                        label: '<?php echo e(($isGlobal ?? false) ? "Presences" : "Kehadiran"); ?>',
                        data: <?php echo json_encode($presenceData ?? []); ?>,
                        borderWidth: 2,
                        tension: .4,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true
                    }]
                },
                options: commonOptions
            });
        }

        const payrollCtx = document.getElementById('payrollChart');
        if (payrollCtx) {
            new Chart(payrollCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($payrollLabels ?? []); ?>,
                    datasets: [{
                        label: 'Payroll',
                        data: <?php echo json_encode($payrollData ?? []); ?>,
                        borderWidth: 1,
                        backgroundColor: '#38bdf8'
                    }]
                },
                options: commonOptions
            });
        }

        const statusCtx = document.getElementById('employeeStatusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($statusLabels ?? []); ?>,
                    datasets: [{
                        data: <?php echo json_encode($statusData ?? []); ?>,
                        backgroundColor: ['#38bdf8', '#10b981', '#fb923c', '#8b5cf6', '#a78bfa']
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    }
});
</script>
<?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/partials/dashboard-content.blade.php ENDPATH**/ ?>