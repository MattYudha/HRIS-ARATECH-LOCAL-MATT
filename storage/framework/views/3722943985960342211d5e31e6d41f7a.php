<?php $__env->startSection('content'); ?>



<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Inventories</li>
        </ol>
    </nav>

    <h3>Inventories</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Inventory List</h4>
                <a href="<?php echo e(route('inventories.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add Item
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="inventory-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th class="text-center">Quantity</th>
                            <th>Location</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
    $(function() {
        $('#inventory-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('inventories.index')); ?>",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'item_type_label', name: 'item_type' },
                { data: 'category.name', name: 'category.name', defaultContent: '-' },
                { data: 'quantity', name: 'quantity', className: 'text-center' },
                { data: 'location', name: 'location' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        // delete confirmation standard
        $(document).on('submit', 'form[action*="destroy"]', function(e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus item inventaris ini?');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventories/index.blade.php ENDPATH**/ ?>