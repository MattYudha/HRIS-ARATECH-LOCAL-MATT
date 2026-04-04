<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Incident Management</h3>
                <p class="text-subtitle text-muted">Track employee sanctions (SP) and awards</p>
            </div>
            <div class="col-md-6 text-md-end">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Incident::class)): ?>
                    <a href="<?php echo e(route('incidents.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Record New Incident</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Severity</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($incident->incident_date->format('d M Y')); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo e($incident->employee->fullname ?? 'N/A'); ?></div>
                                        <small class="text-muted"><?php echo e($incident->employee->department->name ?? 'N/A'); ?></small>
                                    </td>
                                    <td>
                                        <?php
                                            $typeBadge = match(strtolower($incident->type)) {
                                                'sp1', 'sp2', 'sp3', 'peringatan' => 'bg-danger',
                                                'penghargaan', 'award', 'prestasi' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                        ?>
                                        <span class="badge <?php echo e($typeBadge); ?>"><?php echo e(strtoupper($incident->type)); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                            $sevBadge = match($incident->severity) {
                                                'critical' => 'bg-danger',
                                                'high' => 'bg-warning text-dark',
                                                'medium' => 'bg-info',
                                                default => 'bg-light text-dark'
                                            };
                                        ?>
                                        <span class="badge <?php echo e($sevBadge); ?>"><?php echo e(ucfirst($incident->severity)); ?></span>
                                    </td>
                                    <td><?php echo e(Str::limit($incident->description, 50)); ?></td>
                                    <td>
                                        <span class="text-capitalize"><?php echo e($incident->status); ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $incident)): ?>
                                                <a href="<?php echo e(route('incidents.show', $incident->id)); ?>" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $incident)): ?>
                                                <a href="<?php echo e(route('incidents.edit', $incident->id)); ?>" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($incidents->links()); ?>

            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/incidents/index.blade.php ENDPATH**/ ?>