<?php $__env->startSection('content'); ?>
<div class="page-heading mb-4 no-print">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('inventory-dispatches.index')); ?>">Dispatches</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dispatch #<?php echo e($dispatch->barcode); ?></li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h3>Dispatch Details</h3>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i> Print Asset Tag
        </button>
    </div>
</div>


<div id="print-sticker" class="print-only">
    <div class="sticker-wrapper">
        <div class="sticker-qr">
            <?php if(class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')): ?>
                <?php echo QrCode::size(70)->generate($dispatch->barcode); ?>

            <?php else: ?>
                <div class="qr-placeholder">QR</div>
            <?php endif; ?>
        </div>
        <div class="sticker-info">
            <div class="sticker-title">ASSET TAG</div>
            <div class="sticker-barcode"><?php echo e($dispatch->barcode); ?></div>
            <div class="sticker-item"><?php echo e(Str::limit($dispatch->inventory->name, 25)); ?></div>
            <div class="sticker-meta">
                <?php echo e($dispatch->employee->fullname); ?> | <?php echo e($dispatch->area ?? '-'); ?>

            </div>
        </div>
    </div>
</div>

<section class="section no-print">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Overview</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th style="width: 150px;">Barcode ID</th>
                                    <td class="fw-bold text-primary"><?php echo e($dispatch->barcode); ?></td>
                                </tr>
                                <tr>
                                    <th>Item Name</th>
                                    <td><?php echo e($dispatch->inventory->name); ?></td>
                                </tr>
                                <tr>
                                    <th>Item Type</th>
                                    <td><?php echo e($dispatch->inventory->item_type == 'habis_pakai' ? 'Consumable' : 'Asset'); ?></td>
                                </tr>
                                <tr>
                                    <th>Quantity</th>
                                    <td><?php echo e($dispatch->quantity); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th style="width: 150px;">Released To</th>
                                    <td><?php echo e($dispatch->employee->fullname); ?></td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td><?php echo e($dispatch->area ?? '-'); ?> / <?php echo e($dispatch->room ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Dispatch Date</th>
                                    <td><?php echo e($dispatch->dispatch_date->format('d M Y')); ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge bg-success"><?php echo e(ucfirst($dispatch->status)); ?></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($dispatch->notes): ?>
                        <div class="mt-4">
                            <h6>Notes:</h6>
                            <p class="text-muted small"><?php echo e($dispatch->notes); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header">
                    <h4>Current Barcode</h4>
                </div>
                <div class="card-body">
                    <div class="bg-white p-4 border rounded d-inline-block mb-3">
                        <?php if(class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')): ?>
                            <?php echo QrCode::size(150)->generate($dispatch->barcode); ?>

                        <?php else: ?>
                            <div class="border p-3 bg-light font-monospace" style="letter-spacing: 2px;">
                                || ||| | || | ||| |<br>
                                <?php echo e($dispatch->barcode); ?>

                            </div>
                            <small class="text-muted mt-2 d-block">QR Code library not found</small>
                        <?php endif; ?>
                    </div>
                    <div class="small fw-bold"><?php echo e($dispatch->barcode); ?></div>
                    <p class="text-muted small mt-2">Standard view for digital audit.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.print-only { display: none; }

@media print {
    body * { visibility: hidden; }
    #print-sticker, #print-sticker * { visibility: visible; }
    #print-sticker {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 50mm;
        height: 25mm;
        padding: 2mm;
        border: 1px solid #000;
        background: #fff;
    }
    .sticker-wrapper {
        display: flex;
        align-items: center;
        height: 100%;
        overflow: hidden;
    }
    .sticker-qr {
        margin-right: 3mm;
        flex-shrink: 0;
    }
    .sticker-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.1;
        overflow: hidden;
    }
    .sticker-title {
        font-size: 7pt;
        font-weight: bold;
        color: #666;
        margin-bottom: 1px;
    }
    .sticker-barcode {
        font-size: 8pt;
        font-weight: 800;
        font-family: monospace;
        color: #000;
        margin-bottom: 2px;
    }
    .sticker-item {
        font-size: 7.5pt;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }
    .sticker-meta {
        font-size: 6.5pt;
        color: #444;
    }
    .no-print { display: none !important; }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventory_dispatches/show.blade.php ENDPATH**/ ?>