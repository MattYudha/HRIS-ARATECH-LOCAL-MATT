

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <h3>Letter Configuration</h3>
</div>
<div class="page-content">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="<?php echo e(route('letters.index')); ?>" class="btn btn-secondary">Back to Letters</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Company Letterhead Settings</h5>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

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

                    <form action="<?php echo e(route('letter-configurations.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo e(old('company_name', $config->company_name ?? '')); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="company_address" class="form-label">Company Address</label>
                            <textarea class="form-control" id="company_address" name="company_address" rows="2"><?php echo e(old('company_address', $config->company_address ?? '')); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="company_phone" name="company_phone" value="<?php echo e(old('company_phone', $config->company_phone ?? '')); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="company_email" name="company_email" value="<?php echo e(old('company_email', $config->company_email ?? '')); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company_website" class="form-label">Website</label>
                            <input type="text" class="form-control" id="company_website" name="company_website" value="<?php echo e(old('company_website', $config->company_website ?? '')); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="letterhead_footer" class="form-label">Letterhead Footer</label>
                            <textarea class="form-control" id="letterhead_footer" name="letterhead_footer" rows="3"><?php echo e(old('letterhead_footer', $config->letterhead_footer ?? '')); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="letter_number_format" class="form-label">Letter Number Format <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="letter_number_format" name="letter_number_format" value="<?php echo e(old('letter_number_format', $config->letter_number_format ?? '{NUMBER}/{DEPT}/{MONTH}/{YEAR}')); ?>" required>
                            <small class="form-text text-muted">Use {NUMBER}, {DEPT}, {MONTH}, {YEAR} as placeholders</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Configuration</button>
                            <a href="<?php echo e(route('letter-configurations.index')); ?>" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Preview</h5>
                </div>
                <div class="card-body">
                    <p><strong><?php echo e($config->company_name ?? 'Company Name'); ?></strong></p>
                    <p><?php echo e($config->company_address ?? 'Address'); ?></p>
                    <p>
                        <?php echo e($config->company_phone ?? 'Phone'); ?><br>
                        <?php echo e($config->company_email ?? 'Email'); ?>

                    </p>
                    <hr>
                    <small><?php echo e($config->letterhead_footer ?? 'Footer content'); ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letter-configurations/index.blade.php ENDPATH**/ ?>