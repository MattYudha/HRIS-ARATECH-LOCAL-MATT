<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <h3>Create Letter</h3>
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

                    <form action="<?php echo e(route('letters.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="letter_type" class="form-label">Letter Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="letter_type" name="letter_type" required>
                                <option value="">Select Type</option>
                                <option value="official" <?php echo e(old('letter_type') == 'official' ? 'selected' : ''); ?>>Official Letter</option>
                                <option value="memo" <?php echo e(old('letter_type') == 'memo' ? 'selected' : ''); ?>>Memorandum</option>
                                <option value="notice" <?php echo e(old('letter_type') == 'notice' ? 'selected' : ''); ?>>Notice</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="letter_template_id" class="form-label">Use Template (Optional)</label>
                            <select class="form-control" id="letter_template_id" name="letter_template_id">
                                <option value="">-- No Template --</option>
                                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($template->id); ?>" <?php echo e(old('letter_template_id') == $template->id ? 'selected' : ''); ?>><?php echo e($template->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject" name="subject" value="<?php echo e(old('subject')); ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date (for [START_DATE] tag)</label>
                                <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo e(old('start_date')); ?>" placeholder="e.g. 01 Jan 2025">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date (for [END_DATE] tag)</label>
                                <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo e(old('end_date')); ?>" placeholder="e.g. Saat ini / 31 Dec 2026">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose (for [PURPOSE] tag)</label>
                            <input type="text" class="form-control" id="purpose" name="purpose" value="<?php echo e(old('purpose')); ?>" placeholder="e.g. Bank Loan">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="days" class="form-label">Days (for [DAYS] tag)</label>
                                <input type="text" class="form-control" id="days" name="days" value="<?php echo e(old('days')); ?>" placeholder="e.g. 12">
                            </div>
                            <div class="col-md-6">
                                <label for="period" class="form-label">Period (for [PERIOD] tag)</label>
                                <input type="text" class="form-control" id="period" name="period" value="<?php echo e(old('period')); ?>" placeholder="e.g. 1 Tahun">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="recommender_name" class="form-label">Recommender Name (for [RECOMMENDER_NAME] tag)</label>
                            <input type="text" class="form-control" id="recommender_name" name="recommender_name" value="<?php echo e(old('recommender_name')); ?>" placeholder="e.g. John Doe">
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason (for [REASON] tag)</label>
                            <textarea class="form-control" id="reason" name="reason" rows="2" placeholder="e.g. Keperluan Keluarga"><?php echo e(old('reason')); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required><?php echo e(old('content')); ?></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Letter</button>
                            <a href="<?php echo e(route('letters.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const templateSelect = document.getElementById('letter_template_id');
        const contentTextarea = document.getElementById('content');
        const subjectInput = document.getElementById('subject');
        const typeSelect = document.getElementById('letter_type');

        if (templateSelect) {
            templateSelect.addEventListener('change', function() {
                const templateId = this.value;
                
                if (templateId) {
                    // Show loading state
                    const originalText = contentTextarea.value;
                    contentTextarea.value = "Loading template...";
                    
                    fetch(`/letter-templates/${templateId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Populate content
                        if (data.content !== undefined) {
                            contentTextarea.value = data.content || '';
                        } else {
                            contentTextarea.value = '';
                            console.error('Template content is undefined');
                        }
                        
                        // Populate subject if empty
                        if (!subjectInput.value.trim()) {
                            subjectInput.value = data.name;
                        }

                        // Select type if not selected
                        if (!typeSelect.value) {
                             typeSelect.value = data.type;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching template:', error);
                        contentTextarea.value = originalText;
                        alert('Failed to load template content.');
                    });
                } else {
                    // Optionally clear content or do nothing
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letters/create.blade.php ENDPATH**/ ?>