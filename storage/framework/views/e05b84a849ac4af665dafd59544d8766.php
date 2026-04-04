<?php $__env->startSection('content'); ?>
<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Letter Templates</li>
        </ol>
    </nav>

    <h3>Letter Templates</h3>
</div>

<div class="page-content">
    <div class="container-fluid">

        
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="<?php echo e(route('letter-templates.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Create Template
                </a>
            </div>
        </div>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title"><?php echo e($template->name); ?></h5>
                            <p class="card-text text-muted">
                                <?php echo e($template->description ?? '-'); ?>

                            </p>

                            <span class="badge bg-info mb-3 align-self-start">
                                <?php echo e(ucfirst($template->type)); ?>

                            </span>

                            
                            <div class="d-flex gap-2 flex-wrap mt-auto">
                                <button class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contentModal<?php echo e($template->id); ?>"
                                        title="View Content">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <a href="<?php echo e(route('letter-templates.edit', $template)); ?>"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Edit Template">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form method="POST"
                                      action="<?php echo e(route('letter-templates.destroy', $template)); ?>"
                                      class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger delete-confirm" title="Delete Template">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="modal fade" id="contentModal<?php echo e($template->id); ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo e($template->name); ?> – Content Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="border p-3 bg-light">
                                    <?php echo $template->content; ?>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        No templates found.
                        <a href="<?php echo e(route('letter-templates.create')); ?>">Create one</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
    $(function() {
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            window.confirmDelete(form[0], 'Hapus template surat ini?');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letter-templates/index.blade.php ENDPATH**/ ?>