

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <h3>My KPI Dashboard</h3>
</div>

<div class="page-content">
    <div class="container-fluid">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <h6 class="text-primary font-weight-bold mb-1">Composite Score</h6>
                        <h2 class="mb-0"><?php echo e(round($compositeScore, 2)); ?>/100</h2>
                        <small class="text-muted">Overall Performance</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <h6 class="text-success font-weight-bold mb-1">Performance Level</h6>
                        <h4 class="mb-0">
                            <?php switch($performanceLevel):
                                case ('excellent'): ?>
                                    <span class="badge badge-success">Excellent</span>
                                    <?php break; ?>
                                <?php case ('good'): ?>
                                    <span class="badge badge-info">Good</span>
                                    <?php break; ?>
                                <?php case ('satisfactory'): ?>
                                    <span class="badge badge-warning">Satisfactory</span>
                                    <?php break; ?>
                                <?php case ('needs_improvement'): ?>
                                    <span class="badge badge-warning">Needs Improvement</span>
                                    <?php break; ?>
                                <?php default: ?>
                                    <span class="badge badge-danger">Unsatisfactory</span>
                            <?php endswitch; ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <h6 class="text-info font-weight-bold mb-1">KPIs Achieved</h6>
                        <h2 class="mb-0"><?php echo e($kpiRecords->where('status', 'achieved')->count()); ?>/<?php echo e($kpiRecords->count()); ?></h2>
                        <small class="text-muted">Targets Met</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <h6 class="text-warning font-weight-bold mb-1">Period</h6>
                        <h4 class="mb-0"><?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $period)->format('M Y')); ?></h4>
                        <small class="text-muted"><?php echo e($period); ?></small>
                    </div>
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Submission Status -->
        <?php
            $firstRecord = $kpiRecords->first();
            $submissionStatus = $firstRecord->submission_status ?? 'draft';
            $reviewerNotes = $firstRecord->reviewer_notes ?? null;
        ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card <?php echo e($submissionStatus === 'approved' ? 'border-success' : ($submissionStatus === 'rejected' ? 'border-danger' : ($submissionStatus === 'submitted' ? 'border-info' : 'border-secondary'))); ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Status Pengajuan KPI</h6>
                            <?php switch($submissionStatus):
                                case ('draft'): ?>
                                    <span class="badge bg-secondary">Draft</span>
                                    <small class="text-muted ms-2">Belum disubmit ke atasan</small>
                                    <?php break; ?>
                                <?php case ('submitted'): ?>
                                    <span class="badge bg-info">Submitted</span>
                                    <small class="text-muted ms-2">Menunggu persetujuan atasan</small>
                                    <?php break; ?>
                                <?php case ('approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                    <small class="text-muted ms-2">KPI telah disetujui</small>
                                    <?php break; ?>
                                <?php case ('rejected'): ?>
                                    <span class="badge bg-danger">Rejected</span>
                                    <small class="text-muted ms-2">KPI ditolak - silakan perbaiki</small>
                                    <?php break; ?>
                            <?php endswitch; ?>

                            <?php if($submissionStatus === 'rejected' && $reviewerNotes): ?>
                            <div class="alert alert-warning mt-2 mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Catatan dari Atasan:</strong> <?php echo e($reviewerNotes); ?>

                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if($submissionStatus === 'draft' || $submissionStatus === 'rejected'): ?>
                                <?php if($employee->supervisor_id): ?>
                                <form action="<?php echo e(route('kpi.submit', $employee->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="period" value="<?php echo e($period); ?>">
                                    <button type="submit" class="btn btn-outline-primary submit-confirm" data-message="Submit KPI untuk review oleh atasan?">
                                        <i class="bi bi-send"></i> Submit untuk Review
                                    </button>
                                </form>
                                <?php else: ?>
                                <span class="text-muted"><i class="bi bi-info-circle"></i> Tidak ada atasan langsung</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Categories -->
        <?php $__currentLoopData = $kpisByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $records): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?php echo e($category); ?> Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>KPI</th>
                                        <th>Actual Value</th>
                                        <th>Target Value</th>
                                        <th>Achievement %</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($record->kpi->name); ?></strong><br>
                                            <small class="text-muted"><?php echo e($record->kpi->unit); ?></small>
                                        </td>
                                        <td><?php echo e($record->actual_value); ?></td>
                                        <td><?php echo e($record->target_value); ?></td>
                                        <td>
                                            <?php
                                                $achievement = $record->getAchievementPercentage();
                                            ?>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar <?php echo e($achievement >= 100 ? 'bg-success' : ($achievement >= 80 ? 'bg-warning' : 'bg-danger')); ?>" 
                                                     role="progressbar" 
                                                     style="width: <?php echo e(min($achievement, 100)); ?>%" 
                                                     aria-valuenow="<?php echo e($achievement); ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?php echo e(round($achievement, 1)); ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php switch($record->status):
                                                case ('achieved'): ?>
                                                    <span class="badge badge-success">Achieved</span>
                                                    <?php break; ?>
                                                <?php case ('warning'): ?>
                                                    <span class="badge badge-warning">Warning</span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge badge-danger">Critical</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td>
                                            <?php if($record->notes): ?>
                                                <small class="text-muted d-block text-truncate" style="max-width: 150px;" title="<?php echo e($record->notes); ?>">
                                                    <?php echo e($record->notes); ?>

                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(in_array($submissionStatus, ['draft', 'rejected'])): ?>
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-kpi" 
                                                data-id="<?php echo e($record->id); ?>"
                                                data-name="<?php echo e($record->kpi->name); ?>"
                                                data-actual="<?php echo e($record->actual_value); ?>"
                                                data-notes="<?php echo e($record->notes); ?>"
                                                data-auto="<?php echo e($record->kpi->metric_category ? 'true' : 'false'); ?>"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editKPIModal">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php else: ?>
                                            <span class="text-muted small"><i class="bi bi-lock"></i></span>
                                            <?php endif; ?>
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

        <!-- Incidents -->
        <?php if($incidents->count() > 0): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Active Incidents</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(ucfirst(str_replace('_', ' ', $incident->type))); ?></td>
                                        <td><?php echo e($incident->incident_date->format('d M Y')); ?></td>
                                        <td>
                                            <?php switch($incident->severity):
                                                case ('low'): ?>
                                                    <span class="badge badge-info">Low</span>
                                                    <?php break; ?>
                                                <?php case ('medium'): ?>
                                                    <span class="badge badge-warning">Medium</span>
                                                    <?php break; ?>
                                                <?php case ('high'): ?>
                                                    <span class="badge badge-danger">High</span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge badge-dark">Critical</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo e($incident->status === 'resolved' ? 'success' : 'warning'); ?>">
                                                <?php echo e(ucfirst($incident->status)); ?>

                                            </span>
                                        </td>
                                        <td><small><?php echo e($incident->description); ?></small></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-12">
                <a href="<?php echo e(route('reports.export-pdf', $employee->id)); ?>?period=<?php echo e($period); ?>" class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF Report
                </a>
                <a href="<?php echo e(route('kpi.dashboard')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit KPI Modal -->
<div class="modal fade" id="editKPIModal" tabindex="-1" aria-labelledby="editKPIModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editKPIForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="editKPIModalLabel">Update KPI: <span id="modalKPIName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="actualValueGroup" class="mb-3">
                        <label for="actual_value" class="form-label">Nilai Aktual</label>
                        <input type="number" step="0.01" class="form-control" id="actual_value" name="actual_value">
                        <div id="autoCalculatedHint" class="form-text text-info d-none">
                            <i class="bi bi-info-circle"></i> Nilai ini dihitung otomatis oleh sistem dan tidak dapat diubah manual.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan/Penjelasan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan penjelasan mengenai pencapaian Anda..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
$(function() {
    // Edit KPI Modal logic
    $('.edit-kpi').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const actual = $(this).data('actual');
        const notes = $(this).data('notes');
        const isAuto = $(this).data('auto');

        $('#modalKPIName').text(name);
        $('#notes').val(notes);
        
        const form = $('#editKPIForm');
        form.attr('action', `/kpi/record/${id}`);

        if (isAuto) {
            $('#actual_value').val(actual).attr('readonly', true).addClass('bg-light');
            $('#autoCalculatedHint').removeClass('d-none');
        } else {
            $('#actual_value').val(actual).attr('readonly', false).removeClass('bg-light');
            $('#autoCalculatedHint').addClass('d-none');
        }
    });

    $('.submit-confirm').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const msg = $(this).data('message') || 'Konfirmasi tindakan ini?';
        
        Swal.fire({
            title: 'Konfirmasi',
            text: msg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/kpi/dashboard.blade.php ENDPATH**/ ?>