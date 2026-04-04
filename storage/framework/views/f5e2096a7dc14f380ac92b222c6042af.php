<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <h3>Pending KPI Approvals</h3>
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
                    <i class="bi bi-clock"></i>
                    <strong><?php echo e(count($pendingKPIs)); ?></strong> KPI menunggu persetujuan Anda untuk periode <strong><?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y')); ?></strong>
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Pending KPIs Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-check"></i> Daftar KPI Menunggu Persetujuan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Periode</th>
                                        <th>Composite Score</th>
                                        <th>Performance Level</th>
                                        <th>Submitted At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $pendingKPIs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><strong><?php echo e($kpi->fullname); ?></strong></td>
                                        <td><?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $kpi->period)->format('M Y')); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($kpi->composite_score >= 90 ? 'success' : 
                                                ($kpi->composite_score >= 75 ? 'info' : 
                                                ($kpi->composite_score >= 60 ? 'warning' : 'danger'))); ?>">
                                                <?php echo e(round($kpi->composite_score, 2)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php switch($kpi->performance_level):
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
                                        <td><?php echo e($kpi->submitted_at ? \Carbon\Carbon::parse($kpi->submitted_at)->format('d M Y H:i') : '-'); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('kpi.show', $kpi->employee_id)); ?>?period=<?php echo e($kpi->period); ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form action="<?php echo e(route('kpi.approve', $kpi->employee_id)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="period" value="<?php echo e($kpi->period); ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Approve" 
                                                            onclick="return confirm('Setujui KPI <?php echo e($kpi->fullname); ?>?')">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Reject" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($kpi->employee_id); ?>">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal<?php echo e($kpi->employee_id); ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="<?php echo e(route('kpi.reject', $kpi->employee_id)); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="period" value="<?php echo e($kpi->period); ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Tolak KPI - <?php echo e($kpi->fullname); ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Catatan Penolakan</label>
                                                                    <textarea name="notes" class="form-control" rows="3" 
                                                                              placeholder="Berikan alasan penolakan..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Tolak KPI</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-check-circle fs-2 mb-2"></i>
                                            <br>Tidak ada KPI yang menunggu persetujuan.
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

        <div class="row mt-4">
            <div class="col-md-12">
                <a href="<?php echo e(route('kpi.team')); ?>?period=<?php echo e($period); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-people"></i> Lihat Semua Tim
                </a>
                <a href="<?php echo e(route('kpi.department')); ?>?period=<?php echo e($period); ?>" class="btn btn-outline-info">
                    <i class="bi bi-building"></i> Lihat Departemen
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function changePeriod() {
        const period = document.getElementById('periodSelect').value;
        window.location.href = `<?php echo e(route('kpi.pending')); ?>?period=${period}`;
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/kpi/pending.blade.php ENDPATH**/ ?>