<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="page-title w-100">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>View Letter Template</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('letter-templates.index')); ?>">Letter Templates</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e($letterTemplate->name); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        
                        <div class="mb-4">
                            <label class="form-label text-muted">Template Name</label>
                            <h5><?php echo e($letterTemplate->name); ?></h5>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Type</label>
                            <div>
                                <span class="badge bg-info">
                                    <?php echo e(ucfirst($letterTemplate->type)); ?>

                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Description</label>
                            <p class="mb-0"><?php echo e($letterTemplate->description ?? '-'); ?></p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Template Content</label>
                            <div class="border p-4 bg-light rounded">
                                <?php echo $letterTemplate->content; ?>

                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('letter-templates.edit', $letterTemplate)); ?>" class="btn btn-warning">
                                Edit Template
                            </a>
                            <a href="<?php echo e(route('letter-templates.index')); ?>" class="btn btn-secondary">
                                Back to List
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letter-templates/show.blade.php ENDPATH**/ ?>