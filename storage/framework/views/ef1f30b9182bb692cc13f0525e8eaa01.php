<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <h3>Archive Details - <?php echo e($letterArchive->month); ?>/<?php echo e($letterArchive->year); ?></h3>
</div>
<div class="page-content">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="<?php echo e(route('letter-archives.index')); ?>" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Letters</h5>
                    <h2 class="text-primary"><?php echo e($letterArchive->total_letters); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Approved</h5>
                    <h2 class="text-success"><?php echo e($letterArchive->approved_letters); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Printed</h5>
                    <h2 class="text-info"><?php echo e($letterArchive->printed_letters); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Generated</h5>
                    <p class="small"><?php echo e($letterArchive->generated_at->format('d M Y H:i')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Summary</h5>
        </div>
        <div class="card-body">
            <?php echo $letterArchive->summary ?? '<p class="text-muted">No summary available</p>'; ?>

        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letter-archives/show.blade.php ENDPATH**/ ?>