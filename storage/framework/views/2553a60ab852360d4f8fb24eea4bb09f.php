<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Check Out</h3>
                <p class="text-subtitle text-muted">Record your check-out time.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('presences.index')); ?>">Presences</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Check Out</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if(session('warning')): ?>
                    <div class="alert alert-warning"><?php echo e(session('warning')); ?></div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('presences.checkout.process')); ?>" method="POST" id="checkout-form">
                    <?php echo csrf_field(); ?>
                    
                    <!-- GPS Section (only for WFO) -->
                    <div id="gps-section" class="mb-3" style="display: none;">
                        <div id="location-warning" class="alert alert-warning d-none">
                            <b>Note</b> : Mohon izinkan akses lokasi untuk check-out WFO.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Latitude</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="latitude" id="latitude" readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="getGPSLocation()">
                                    <i class="bi bi-geo-alt"></i> Ambil Lokasi
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" class="form-control" name="longitude" id="longitude" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><b>Jarak ke Kantor</b></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="distance-display" readonly placeholder="Mencari lokasi...">
                                <span class="input-group-text">Meter</span>
                            </div>
                            <small class="text-muted" id="distance-info">Maksimum untuk <?php echo e($officeLocationConfig['name']); ?>: <?php echo e($officeLocationConfig['radius']); ?>m</small>
                        </div>

                        <input type="hidden" name="accuracy" id="accuracy">
                    </div>

                    <div class="mb-3">
                        <p><strong>Check-in Time:</strong> <?php echo e($checkInTime ?? 'N/A'); ?></p>
                        <p><strong>Work Type:</strong> <span class="badge bg-primary"><?php echo e($presence->work_type ?? 'WFO'); ?></span></p>
                        <p><strong>Office Location:</strong> <?php echo e($officeLocationConfig['name']); ?></p>
                        <p><strong>Current Time:</strong> <span id="current-time"></span></p>
                    </div>

                    <button type="submit" id="btn-checkout" class="btn btn-success">Check Out</button>
                    <a href="<?php echo e(route('presences.index')); ?>" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    // Configuration
    const officeLat = <?php echo json_encode($officeLocationConfig['latitude'], 15, 512) ?>;
    const officeLon = <?php echo json_encode($officeLocationConfig['longitude'], 15, 512) ?>;
    const thresholdMeters = <?php echo json_encode($officeLocationConfig['radius'], 15, 512) ?>;

    // GPS State
    let gpsWatchId = null;
    let gpsRetryInterval = null;
    let gpsLastUpdateTime = 0;

    // 🚀 AGGRESSIVE GPS: Auto-start on page load
    function startAggressiveGPS() {
        console.log("🚀 AGGRESSIVE GPS: Starting continuous location tracking...");
        
        if (typeof window !== 'undefined' && window.isSecureContext === false) {
            console.error('🔒 GPS BLOCKED: Site must use HTTPS');
            alert('⚠️ GPS hanya bekerja di HTTPS. Hubungi IT untuk mengaktifkan SSL.');
            return;
        }

        if (!navigator.geolocation) {
            console.error('❌ Geolocation not supported');
            return;
        }

        const onSuccess = (position) => {
            gpsLastUpdateTime = Date.now();
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const acc = position.coords.accuracy;
            
            console.log(`📍 GPS UPDATE: ${lat.toFixed(6)}, ${lon.toFixed(6)} (±${Math.round(acc)}m)`);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lon;
            document.getElementById('accuracy').value = acc;

            if (acc <= 1) {
                alert('⚠️ Akurasi GPS mencurigakan. Jangan gunakan Fake GPS!');
            }

            const distDegrees = Math.sqrt(Math.pow(lat - officeLat, 2) + Math.pow(lon - officeLon, 2));
            const distMeters = Math.round(distDegrees * 111320);
            
            const distDisplay = document.getElementById('distance-display');
            if (distDisplay) distDisplay.value = distMeters;

            const btnCheckout = document.getElementById('btn-checkout');
            if (distMeters <= thresholdMeters) {
                console.log(`✅ Location OK: ${distMeters}m`);
                if (btnCheckout) btnCheckout.removeAttribute('disabled');
            } else {
                console.warn(`❌ Outside radius: ${distMeters}m > ${thresholdMeters}m`);
                if (btnCheckout) btnCheckout.setAttribute('disabled', 'disabled');
            }
        };

        const onError = (error) => {
            let msg = '❌ GPS Error: ';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    msg += 'Izin ditolak. Aktifkan di Settings → Site Permissions → Location';
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg += 'Sinyal tidak tersedia. Aktifkan GPS mode High Accuracy';
                    break;
                case error.TIMEOUT:
                    msg += 'Timeout. Pindah ke area terbuka';
                    break;
                default:
                    msg += 'Unknown error';
                    break;
            }
            console.error(msg);
            alert(msg);
        };

        if (gpsWatchId !== null) {
            navigator.geolocation.clearWatch(gpsWatchId);
        }

        try {
            gpsWatchId = navigator.geolocation.watchPosition(
                onSuccess,
                onError,
                {
                    enableHighAccuracy: true,
                    timeout: 45000,
                    maximumAge: 5000
                }
            );
            console.log(`📡 GPS Watch started (ID: ${gpsWatchId})`);
        } catch (e) {
            console.error(`Exception: ${e.message}`);
        }

        // Auto-restart if stalled
        if (gpsRetryInterval) clearInterval(gpsRetryInterval);
        gpsRetryInterval = setInterval(() => {
            const timeSinceUpdate = Date.now() - gpsLastUpdateTime;
            if (timeSinceUpdate > 60000 && gpsWatchId !== null) {
                console.warn('⏱️ GPS stalled for 60s, restarting...');
                navigator.geolocation.clearWatch(gpsWatchId);
                gpsWatchId = null;
                startAggressiveGPS();
            }
        }, 30000);
    }

    // Manual button (fallback)
    function getGPSLocation() {
        console.log('🔄 Manual GPS refresh');
        navigator.geolocation.getCurrentPosition(
            (pos) => console.log('Manual GPS OK: ' + pos.coords.latitude),
            (err) => console.error('Manual GPS failed: ' + err.message),
            { enableHighAccuracy: true, timeout: 30000, maximumAge: 0 }
        );
    }

    // 🚀 AUTO-START ON PAGE LOAD
    document.addEventListener('DOMContentLoaded', () => {
        console.log("🚀 Checkout page: Auto-starting GPS...");
        startAggressiveGPS();
    });
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/presences/checkout.blade.php ENDPATH**/ ?>