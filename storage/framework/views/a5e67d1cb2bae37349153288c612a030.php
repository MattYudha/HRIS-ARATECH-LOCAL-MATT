<?php $__env->startSection('content'); ?>
<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('letter-templates.index')); ?>">Letter Templates</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Template</li>
        </ol>
    </nav>

    <h3>Edit Letter Template</h3>
</div>

<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('letter-templates.update', $letterTemplate)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       id="name"
                                       name="name"
                                       value="<?php echo e(old('name', $letterTemplate->name)); ?>"
                                       required>
                            </div>

                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control"
                                          id="description"
                                          name="description"
                                          rows="2"><?php echo e(old('description', $letterTemplate->description)); ?></textarea>
                            </div>

                            
                            <div class="mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="official" <?php echo e(old('type', $letterTemplate->type) == 'official' ? 'selected' : ''); ?>>Official Letter</option>
                                    <option value="memo" <?php echo e(old('type', $letterTemplate->type) == 'memo' ? 'selected' : ''); ?>>Memorandum</option>
                                    <option value="notice" <?php echo e(old('type', $letterTemplate->type) == 'notice' ? 'selected' : ''); ?>>Notice</option>
                                </select>
                            </div>

                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Template Content <span class="text-danger">*</span></label>
                                <textarea class="form-control"
                                          id="content"
                                          name="content"
                                          rows="10"
                                          placeholder="Enter the content here"
                                          required><?php echo e(old('content', $letterTemplate->content)); ?></textarea>
                                <small class="form-text text-muted">You can use HTML tags for formatting</small>
                            </div>

                            
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-pencil-square me-1"></i> Update Template
                                </button>
                                <a href="<?php echo e(route('letter-templates.index')); ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letter-templates/edit.blade.php ENDPATH**/ ?>