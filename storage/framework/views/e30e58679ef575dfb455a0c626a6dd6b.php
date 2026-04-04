<?php $__env->startSection('content'); ?>
<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Letter Archives</li>
        </ol>
    </nav>

    <h3>Letter Archives</h3>
</div>

<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Total Letters</th>
                            <th>Approved</th>
                            <th>Printed</th>
                            <th>Generated Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $archives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $archive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($archive->month); ?></td>
                                <td><?php echo e($archive->year); ?></td>
                                <td><span class="badge bg-primary"><?php echo e($archive->total_letters); ?></span></td>
                                <td><span class="badge bg-success"><?php echo e($archive->approved_letters); ?></span></td>
                                <td><span class="badge bg-info"><?php echo e($archive->printed_letters); ?></span></td>
                                <td><?php echo e($archive->generated_at->format('d M Y H:i')); ?></td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('letter-archives.show', $archive)); ?>" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View Archive">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No archives found yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letter-archives/index.blade.php ENDPATH**/ ?>