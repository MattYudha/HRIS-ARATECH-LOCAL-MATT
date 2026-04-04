<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Inventory Requests</h3>
                <p class="text-subtitle text-muted">Pengajuan barang inventori atau perbaikan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inventory Requests</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <a href="<?php echo e(route('inventory-requests.create')); ?>" class="btn btn-outline-primary ms-auto">
                        <i class="bi bi-plus-lg"></i> New Request
                    </a>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="inventory-requests-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Employee</th>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Requested At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(function() {
        $('#inventory-requests-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('inventory-requests.index')); ?>",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'employee.fullname', name: 'employee.fullname' },
                { data: 'item_display', name: 'item_display' },
                { data: 'request_type', name: 'request_type' },
                { data: 'quantity', name: 'quantity' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        // delete confirmation standard
        $(document).on('submit', 'form[action*="destroy"]', function(e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus pengajuan inventaris ini?');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventory_requests/index.blade.php ENDPATH**/ ?>