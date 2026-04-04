

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <h3>Create Letter Template</h3>
</div>
<div class="page-content">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
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

                    <form action="<?php echo e(route('letter-templates.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2"><?php echo e(old('description')); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="official" <?php echo e(old('type') == 'official' ? 'selected' : ''); ?>>Official Letter</option>
                                <option value="memo" <?php echo e(old('type') == 'memo' ? 'selected' : ''); ?>>Memorandum</option>
                                <option value="notice" <?php echo e(old('type') == 'notice' ? 'selected' : ''); ?>>Notice</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Template Content <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required><?php echo e(old('content')); ?></textarea>
                            <small class="form-text text-muted">You can use HTML tags for formatting</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Template</button>
                            <a href="<?php echo e(route('letter-templates.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letter-templates/create.blade.php ENDPATH**/ ?>