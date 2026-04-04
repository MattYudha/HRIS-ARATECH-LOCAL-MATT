<?php $__env->startSection('content'); ?>
<div class="page-heading mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Logistics Shipments</li>
        </ol>
    </nav>
    <h3>Logistics & Shipment Tracking</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="shipment-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>no urut</th>
                            <th>Tracking No</th>
                            <th>Carrier</th>
                            <th>Related To</th>
                            <th>Origin / Destination</th>
                            <th>Status</th>
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
        var table = $('#shipment-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(route('logistics-shipments.index')); ?>",
                error: function (xhr, error, thrown) {
                    console.error('DataTables Error:', error, thrown);
                    console.log('Response:', xhr.responseText);
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'tracking_number', name: 'tracking_number', defaultContent: '<i class="text-muted">No Tracking</i>' },
                { data: 'carrier', name: 'carrier', defaultContent: '-' },
                { data: 'related_to', name: 'related_to', orderable: false },
                { data: 'origin_dest', name: 'origin', orderable: false },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/logistics_shipments/index.blade.php ENDPATH**/ ?>