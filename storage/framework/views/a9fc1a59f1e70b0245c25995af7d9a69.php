<?php $__env->startSection('content'); ?>
<div class="page-heading mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('inventory-categories.index')); ?>">Inventory Categories</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e($category->name); ?></li>
        </ol>
    </nav>
    <h3>Category: <?php echo e($category->name); ?></h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <!-- Category Info -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">Category Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="font-bold">Name</label>
                            <p class="text-muted"><?php echo e($category->name); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Description</label>
                            <p class="text-muted"><?php echo e($category->description ?: 'No description provided.'); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Total Items</label>
                            <p class="text-muted"><?php echo e($category->inventories->count()); ?> items</p>
                        </div>
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('inventory-categories.edit', $category->id)); ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="<?php echo e(route('inventory-categories.index')); ?>" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items in this Category -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Items in <?php echo e($category->name); ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if($category->inventories->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="items-table">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>SKU</th>
                                            <th class="text-center">Stock</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $category->inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($item->name); ?></td>
                                                <td><?php echo e($item->sku); ?></td>
                                                <td class="text-center">
                                                    <span class="badge <?php echo e($item->stock > 0 ? 'bg-success' : 'bg-danger'); ?>">
                                                        <?php echo e($item->stock); ?>

                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?php echo e(route('inventories.show', $item->id)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted italic">No items found in this category.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(function() {
        $('#items-table').DataTable();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventory-categories/show.blade.php ENDPATH**/ ?>