<?php $__env->startSection('content'); ?>



<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('inventory-categories.index')); ?>">Inventory Categories</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Category</li>
        </ol>
    </nav>

    <h3>Add Category</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12"> 
                <div class="card">
                    <div class="card-body">

                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('inventory-categories.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Category Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control"
                                       value="<?php echo e(old('name')); ?>"
                                       required
                                       placeholder="Enter category name">
                            </div>

                            
                            <div class="mb-3">
                                <label for="items_count" class="form-label">
                                    Items Count
                                </label>
                                <input type="number"
                                       name="items_count"
                                       id="items_count"
                                       class="form-control"
                                       value="<?php echo e(old('items_count', 0)); ?>"
                                       min="0"
                                       placeholder="Number of items">
                            </div>

                            
                            <div class="mb-4">
                                <label for="description" class="form-label">
                                    Description
                                </label>
                                <textarea name="description"
                                          id="description"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Enter description"><?php echo e(old('description')); ?></textarea>
                            </div>

                            
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="bi bi-check-circle me-1"></i> Create
                                </button>

                                <a href="<?php echo e(route('inventory-categories.index')); ?>"
                                   class="btn btn-secondary px-5">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventory-categories/create.blade.php ENDPATH**/ ?>