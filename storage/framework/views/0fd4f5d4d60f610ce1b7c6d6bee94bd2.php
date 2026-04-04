<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <div class="d-flex align-items-center">
                    <h3><i class="bi bi-book"></i> Knowledge Base</h3>
                    <?php if(Auth::user()->hasAccess('knowledge_base')): ?>
                        <a href="<?php echo e(route('knowledge-base.create')); ?>" class="btn btn-primary btn-sm ms-3">
                            <i class="bi bi-plus-lg"></i> Tambah Artikel
                        </a>
                    <?php endif; ?>
                </div>
                <p class="text-subtitle text-muted">Wikipedia internal perusahaan — panduan lengkap, kebijakan, dan FAQ.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Knowledge Base</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-12 col-md-8 mx-auto">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" id="kb-search" placeholder="Cari panduan, kebijakan, atau FAQ..." autocomplete="off">
                <span class="input-group-text bg-white" id="search-count" style="display:none; font-size:0.8rem;"></span>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards -->
    <div class="row mb-4" id="kb-nav-cards">
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-primary h-100 shadow-sm kb-nav-card" style="cursor:pointer" onclick="showSection('user-guide')">
                <div class="card-body text-center py-4">
                    <i class="bi bi-display" style="font-size: 2rem; color: #435ebe;"></i>
                    <h6 class="card-title text-primary mt-2 mb-1">Panduan Aplikasi</h6>
                    <p class="text-muted small mb-0">Tutorial fitur HRIS</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-success h-100 shadow-sm kb-nav-card" style="cursor:pointer" onclick="showSection('company-policy')">
                <div class="card-body text-center py-4">
                    <i class="bi bi-shield-check" style="font-size: 2rem; color: #198754;"></i>
                    <h6 class="card-title text-success mt-2 mb-1">Kebijakan Perusahaan</h6>
                    <p class="text-muted small mb-0">Peraturan & SOP</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-warning h-100 shadow-sm kb-nav-card" style="cursor:pointer" onclick="showSection('admin-guide')">
                <div class="card-body text-center py-4">
                    <i class="bi bi-gear" style="font-size: 2rem; color: #ffc107;"></i>
                    <h6 class="card-title text-warning mt-2 mb-1">Panduan Admin/HR Administrator</h6>
                    <p class="text-muted small mb-0">Manajemen sistem</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-info h-100 shadow-sm kb-nav-card" style="cursor:pointer" onclick="showSection('faq')">
                <div class="card-body text-center py-4">
                    <i class="bi bi-question-circle" style="font-size: 2rem; color: #0dcaf0;"></i>
                    <h6 class="card-title text-info mt-2 mb-1">FAQ</h6>
                    <p class="text-muted small mb-0">Pertanyaan umum</p>
                </div>
            </div>
        </div>
    </div>

    
    
    
    <section id="section-user-guide" class="kb-section">
        <div class="d-flex align-items-center mb-3 kb-section-header">
            <button class="btn btn-sm btn-outline-secondary me-2" onclick="showAllSections()"><i class="bi bi-arrow-left"></i></button>
            <h4 class="mb-0"><i class="bi bi-display text-primary"></i> Panduan Penggunaan Aplikasi</h4>
        </div>

        
        <?php if(isset($categories['user-guide'])): ?>
            <?php $__currentLoopData = $categories['user-guide']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card mb-3 kb-item" data-keywords="<?php echo e($article->keywords); ?>">
                    <div class="card-header bg-white d-flex align-items-center" style="cursor:pointer">
                        <div class="flex-grow-1" data-bs-toggle="collapse" data-bs-target="#article-<?php echo e($article->id); ?>">
                            <h5 class="mb-0">
                                <i class="bi bi-file-earmark-text text-primary me-2"></i> <?php echo e($article->title); ?>

                                <i class="bi bi-chevron-down float-end"></i>
                            </h5>
                        </div>
                        <?php if(Auth::user()->hasAccess('knowledge_base')): ?>
                            <div class="ms-3">
                                <a href="<?php echo e(route('knowledge-base.edit', $article->id)); ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('knowledge-base.destroy', $article->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="article-<?php echo e($article->id); ?>" class="collapse">
                        <div class="card-body">
                            <?php echo $article->content; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        
        <div class="card mb-3 kb-item" data-keywords="dashboard beranda home ringkasan overview statistik grafik chart">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-dashboard">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-grid-fill text-primary me-2"></i> Dashboard
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-dashboard" class="collapse">
                <div class="card-body">
                    <p>Dashboard adalah halaman utama setelah login. Di sini Anda dapat melihat ringkasan informasi penting:</p>
                    <h6 class="fw-bold">Informasi yang Ditampilkan:</h6>
                    <ul>
                        <li><strong>Status Kehadiran Hari Ini</strong> — Apakah Anda sudah check-in atau belum.</li>
                        <li><strong>Statistik Kehadiran</strong> — Total hadir, terlambat, izin, dan absen bulan ini.</li>
                        <li><strong>Task Aktif</strong> — Jumlah task yang masih pending atau in-progress.</li>
                        <li><strong>Pengumuman / Notifikasi</strong> — Info penting dari HR Administrator atau manajemen.</li>
                        <li><strong>Kalender Kehadiran</strong> — Visual kalender untuk melihat pola kehadiran.</li>
                    </ul>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-lightbulb"></i> <strong>Tips:</strong> Biasakan cek Dashboard setiap pagi setelah login untuk melihat informasi terkini.
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="absensi presensi clock in out checkin checkout wfo wfh wfa gps fingerprint face wajah kehadiran hadir telat terlambat">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-absensi">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-clock-history text-primary me-2"></i> Panduan Absensi (Clock In / Clock Out)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-absensi" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold text-primary">A. Cara Clock-In (Check In)</h6>
                    <ol>
                        <li>Buka menu <strong>Presences</strong> di sidebar kiri bawah.</li>
                        <li>Klik tombol <strong>"Create New Presence"</strong>.</li>
                        <li>Pilih tipe kerja:
                            <div class="row mt-2 mb-2">
                                <div class="col-md-4">
                                    <div class="card border-primary mb-2">
                                        <div class="card-body p-2">
                                            <span class="badge bg-primary">WFO</span> <strong>Work From Office</strong>
                                            <ul class="small mt-1 mb-0">
                                                <li>GPS dalam radius kantor (1 km)</li>
                                                <li>WiFi: 4 SSID terdaftar</li>
                                                <li>Verifikasi wajah</li>
                                                <li>Fingerprint browser</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success mb-2">
                                        <div class="card-body p-2">
                                            <span class="badge bg-success">WFH</span> <strong>Work From Home</strong>
                                            <ul class="small mt-1 mb-0">
                                                <li>GPS lokasi bebas</li>
                                                <li>WiFi bebas (+ Other)</li>
                                                <li>Verifikasi wajah</li>
                                                <li>Fingerprint browser</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-info mb-2">
                                        <div class="card-body p-2">
                                            <span class="badge bg-info">WFA</span> <strong>Work From Anywhere</strong>
                                            <ul class="small mt-1 mb-0">
                                                <li>GPS lokasi bebas</li>
                                                <li>WiFi bebas (+ Other)</li>
                                                <li>Verifikasi wajah</li>
                                                <li>Fingerprint browser</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>Tunggu semua validasi berstatus <span class="badge bg-success">✅</span> (hijau).</li>
                        <li>Klik tombol <strong>"Present"</strong> untuk check-in.</li>
                    </ol>

                    <h6 class="fw-bold text-primary mt-4">B. Cara Clock-Out (Check Out)</h6>
                    <ol>
                        <li>Buka menu <strong>Presences</strong>.</li>
                        <li>Klik tombol <strong>"Check Out"</strong> pada tabel presensi hari ini.</li>
                        <li>Konfirmasi waktu check-out Anda.</li>
                    </ol>

                    <h6 class="fw-bold text-primary mt-4">C. Kalender Kehadiran</h6>
                    <p>Buka menu <strong>Presences → Calendar</strong> untuk melihat histori kehadiran dalam tampilan kalender bulanan.</p>

                    <h6 class="fw-bold text-primary mt-4">D. Statistik Kehadiran</h6>
                    <p>Buka menu <strong>Presences → Statistics</strong> untuk melihat rangkuman kehadiran Anda, termasuk total hadir, terlambat, dan rata-rata jam kerja.</p>

                    <h6 class="fw-bold text-primary mt-4">E. Export Data Kehadiran</h6>
                    <p>Klik tombol <strong>Export CSV</strong> di halaman Presences untuk mengunduh data kehadiran dalam format CSV.</p>

                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Penting:</strong>
                        <ul class="mb-0 mt-1">
                            <li>WiFi WFO yang terdaftar: <code>UNPAM VIKTOR</code>, <code>Serhan 2</code>, <code>Serhan</code>, <code>S53s</code></li>
                            <li>Radius kantor: <strong>1 km</strong> dari koordinat kantor.</li>
                            <li>GPS dan kamera harus diizinkan di browser.</li>
                            <li>Gunakan browser yang sama setiap kali (fingerprint harus cocok).</li>
                            <li>Check-in setelah <strong>08:15</strong> = terlambat.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="cuti izin leave request pengajuan approve reject sakit tahunan melahirkan">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-cuti">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-calendar-check text-success me-2"></i> Pengajuan Cuti / Izin (Leave Request)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-cuti" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold text-success">Langkah Pengajuan:</h6>
                    <ol>
                        <li>Buka menu <strong>HR Administrator Management → Leave Requests</strong>.</li>
                        <li>Klik <strong>"Create New Leave Request"</strong>.</li>
                        <li>Isi formulir:
                            <ul>
                                <li><strong>Jenis Cuti</strong> — Pilih tipe (Cuti Tahunan, Sakit, Izin, dll).</li>
                                <li><strong>Tanggal Mulai & Selesai</strong> — Pilih rentang tanggal.</li>
                                <li><strong>Alasan</strong> — Tulis alasan cuti.</li>
                                <li><strong>Lampiran</strong> — Upload dokumen pendukung jika diperlukan.</li>
                            </ul>
                        </li>
                        <li>Klik <strong>"Submit"</strong>.</li>
                        <li>Tunggu persetujuan dari atasan/HR Administrator.</li>
                    </ol>

                    <h6 class="fw-bold mt-3">Status Pengajuan:</h6>
                    <ul>
                        <li><span class="badge bg-warning text-dark">Pending</span> — Menunggu persetujuan.</li>
                        <li><span class="badge bg-success">Confirmed</span> — Disetujui oleh HR Administrator/atasan.</li>
                        <li><span class="badge bg-danger">Rejected</span> — Ditolak (lihat alasan penolakan).</li>
                    </ul>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Pengajuan cuti minimal <strong>3 hari kerja</strong> sebelumnya (kecuali darurat/sakit).
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="task tugas kerjaan komentar comment done pending progress assignment pekerjaan">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-task">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-list-task text-danger me-2"></i> Manajemen Task
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-task" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">Melihat Daftar Task:</h6>
                    <ol>
                        <li>Buka menu <strong>HR Administrator Management → Tasks</strong>.</li>
                        <li>Anda akan melihat semua task yang diberikan kepada Anda.</li>
                        <li>Gunakan filter/pencarian untuk menemukan task tertentu.</li>
                    </ol>

                    <h6 class="fw-bold mt-3">Detail & Komentar Task:</h6>
                    <ol>
                        <li>Klik ikon <i class="bi bi-eye"></i> untuk melihat detail task.</li>
                        <li>Di halaman detail:
                            <ul>
                                <li><strong>Baca deskripsi</strong> task dan deadline.</li>
                                <li><strong>Berikan komentar</strong> — update progress, pertanyaan, atau catatan.</li>
                                <li><strong>Hapus komentar</strong> — hanya komentar Anda sendiri (atau HR Administrator/Manager / Unit Head).</li>
                            </ul>
                        </li>
                    </ol>

                    <h6 class="fw-bold mt-3">Mengubah Status Task:</h6>
                    <ul>
                        <li><i class="bi bi-check-circle text-success"></i> Klik untuk menandai task <span class="badge bg-success">Done</span>.</li>
                        <li><i class="bi bi-arrow-counterclockwise text-warning"></i> Klik untuk mengembalikan ke <span class="badge bg-warning text-dark">Pending</span>.</li>
                    </ul>

                    <h6 class="fw-bold mt-3">Status Task:</h6>
                    <ul>
                        <li><span class="badge bg-warning text-dark">Pending</span> — Belum dikerjakan.</li>
                        <li><span class="badge bg-info">On Progress</span> — Sedang dikerjakan.</li>
                        <li><span class="badge bg-success">Done</span> — Selesai (timestamp otomatis tersimpan).</li>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="slip gaji payroll salary payslip download unduh gaji bulanan take home pay tunjangan potongan">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-payroll">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-cash-stack text-warning me-2"></i> Melihat Slip Gaji (Payroll)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-payroll" class="collapse">
                <div class="card-body">
                    <ol>
                        <li>Buka menu <strong>Payroll & KPI → Payrolls</strong>.</li>
                        <li>Pilih periode gaji yang ingin dilihat.</li>
                        <li>Klik ikon <i class="bi bi-eye"></i> untuk melihat detail.</li>
                    </ol>

                    <h6 class="fw-bold mt-3">Komponen Slip Gaji:</h6>
                    <table class="table table-bordered table-sm">
                        <tr><td><i class="bi bi-plus-circle text-success"></i> Gaji Pokok</td><td>Sesuai kontrak kerja</td></tr>
                        <tr><td><i class="bi bi-plus-circle text-success"></i> Tunjangan Transport</td><td>Per bulan</td></tr>
                        <tr><td><i class="bi bi-plus-circle text-success"></i> Tunjangan Makan</td><td>Per hari kerja</td></tr>
                        <tr><td><i class="bi bi-dash-circle text-danger"></i> Potongan BPJS</td><td>Sesuai regulasi</td></tr>
                        <tr><td><i class="bi bi-dash-circle text-danger"></i> Potongan PPh 21</td><td>Pajak penghasilan</td></tr>
                        <tr class="table-success fw-bold"><td>= Take Home Pay</td><td>Gaji bersih yang diterima</td></tr>
                    </table>

                    <div class="alert alert-secondary">
                        <i class="bi bi-lock"></i> Slip gaji bersifat <strong>rahasia</strong> dan hanya bisa diakses oleh karyawan bersangkutan dan HR Administrator.
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="kpi kinerja performance penilaian target evaluasi score trend team department submit approve reject">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-kpi">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-graph-up-arrow text-success me-2"></i> KPI (Key Performance Indicator)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-kpi" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">A. KPI Dashboard (Semua Karyawan)</h6>
                    <ol>
                        <li>Buka menu <strong>Payroll & KPI → KPI Dashboard</strong>.</li>
                        <li>Lihat skor KPI Anda saat ini dan breakdown per kategori.</li>
                        <li>Klik <strong>"Submit KPI"</strong> untuk mengirim self-assessment.</li>
                    </ol>

                    <h6 class="fw-bold mt-3">B. Trend KPI</h6>
                    <p>Lihat grafik perkembangan KPI Anda dari bulan ke bulan untuk memantau progress.</p>

                    <h6 class="fw-bold mt-3">C. Team KPI (Manager / Unit Head/HR Administrator)</h6>
                    <ol>
                        <li>Buka <strong>Payroll & KPI → Team KPI</strong>.</li>
                        <li>Lihat skor KPI semua anggota tim Anda.</li>
                        <li>Klik nama karyawan untuk melihat detail.</li>
                    </ol>

                    <h6 class="fw-bold mt-3">D. Department KPI (Manager / Unit Head/HR Administrator)</h6>
                    <p>Overview KPI per departemen untuk membandingkan performa antar divisi.</p>

                    <h6 class="fw-bold mt-3">E. Pending Approvals (Manager / Unit Head/HR Administrator)</h6>
                    <ol>
                        <li>Buka <strong>Payroll & KPI → Pending Approvals</strong>.</li>
                        <li>Review KPI yang di-submit oleh anggota tim.</li>
                        <li>Pilih <strong>Approve</strong> atau <strong>Reject</strong> dengan catatan.</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="inventaris barang pinjam request peralatan kantor usage log kategori stok stock gudang">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-inventory">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-box-seam text-info me-2"></i> Inventory (Inventaris Kantor)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-inventory" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">A. Melihat Inventaris</h6>
                    <p>Buka <strong>Inventory → Inventories</strong> (HR Administrator/Admin) untuk melihat daftar semua barang kantor beserta stok.</p>

                    <h6 class="fw-bold mt-3">B. Membuat Permintaan Barang (Semua Karyawan)</h6>
                    <ol>
                        <li>Buka <strong>Inventory → Requests</strong>.</li>
                        <li>Klik <strong>"Create New Request"</strong>.</li>
                        <li>Pilih barang, jumlah, dan alasan peminjaman.</li>
                        <li>Klik <strong>"Submit"</strong>.</li>
                        <li>Tunggu persetujuan dari HR Administrator/Admin.</li>
                    </ol>

                    <h6 class="fw-bold mt-3">C. Usage Logs (Semua Karyawan)</h6>
                    <p>Buka <strong>Inventory → Usage Logs</strong> untuk melihat histori penggunaan barang inventaris.</p>

                    <h6 class="fw-bold mt-3">D. Status Permintaan:</h6>
                    <ul>
                        <li><span class="badge bg-warning text-dark">Pending</span> — Menunggu persetujuan.</li>
                        <li><span class="badge bg-success">Approved</span> — Disetujui, barang bisa diambil.</li>
                        <li><span class="badge bg-danger">Rejected</span> — Ditolak.</li>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="surat letter keterangan kerja referensi paklaring cetak print template submit approve reject tanda tangan signature digital">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-letters">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-envelope-paper text-primary me-2"></i> Surat & Tanda Tangan Digital (Letters & Signatures)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-letters" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">A. Membuat Surat Baru</h6>
                    <ol>
                        <li>Buka menu <strong>Letters → Letters</strong>.</li>
                        <li>Klik <strong>"Create New Letter"</strong>.</li>
                        <li>Pilih template surat (Surat Keterangan Kerja, Surat Tugas, dll).</li>
                        <li>Isi data yang diperlukan.</li>
                        <li>Klik <strong>"Save"</strong> lalu <strong>"Submit"</strong> untuk dikirim ke HR Administrator.</li>
                    </ol>

                    <h6 class="fw-bold mt-3">B. Alur Persetujuan Surat</h6>
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                        <span class="badge bg-secondary">Draft</span> <i class="bi bi-arrow-right"></i>
                        <span class="badge bg-warning text-dark">Submitted</span> <i class="bi bi-arrow-right"></i>
                        <span class="badge bg-success">Approved</span> <i class="bi bi-arrow-right"></i>
                        <span class="badge bg-primary">Printed / Exported</span>
                    </div>
                    <p class="small text-muted">Atau bisa <span class="badge bg-danger">Rejected</span> oleh HR Administrator dengan alasan.</p>

                    <h6 class="fw-bold mt-3">C. Cetak & Export Surat</h6>
                    <ul>
                        <li>Setelah surat disetujui, klik <strong>"Print"</strong> untuk cetak.</li>
                        <li>Klik <strong>"Export"</strong> untuk unduh dalam format PDF.</li>
                    </ul>

                    <h6 class="fw-bold mt-3">D. Tanda Tangan Digital</h6>
                    <ol>
                        <li>Saat surat memerlukan tanda tangan, Anda akan diarahkan ke <strong>Signature Pad</strong>.</li>
                        <li>Gambar tanda tangan Anda menggunakan mouse/touchscreen.</li>
                        <li>Tanda tangan akan diverifikasi dan disimpan secara digital.</li>
                        <li>Cek <strong>Signature Logs</strong> untuk melihat histori tanda tangan.</li>
                    </ol>

                    <h6 class="fw-bold mt-3">E. Verifikasi Tanda Tangan</h6>
                    <p>Setiap tanda tangan memiliki kode unik yang bisa diverifikasi keasliannya melalui fitur <strong>Verify Signature</strong>.</p>

                    <h6 class="fw-bold mt-3">F. Arsip Surat (HR Administrator/Admin)</h6>
                    <p>Buka <strong>Letters → Archives</strong> untuk melihat semua surat yang sudah diarsipkan.</p>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="profil data pribadi update edit alamat nomor rekening telepon email foto password">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-profile">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-person-gear text-secondary me-2"></i> Update Data Pribadi (My Profile)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-profile" class="collapse">
                <div class="card-body">
                    <ol>
                        <li>Klik <strong>My Profile</strong> di sidebar kiri bawah.</li>
                        <li>Klik <strong>"Edit"</strong>.</li>
                        <li>Ubah data: nama, alamat, telepon, email, rekening bank, foto profil.</li>
                        <li>Klik <strong>"Save"</strong>.</li>
                    </ol>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> Perubahan data kritis (nama, rekening) memerlukan <strong>persetujuan HR Administrator</strong> melalui menu Employee Approvals.
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="laporan report monthly recap executive bulanan export pdf csv download">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#guide-reports">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-file-earmark-bar-graph text-purple me-2" style="color:#6f42c1"></i> Laporan (Reports)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="guide-reports" class="collapse">
                <div class="card-body">
                    <p><em>Menu ini hanya tersedia untuk <strong>Manager / Unit Head</strong> dan <strong>HR Administrator/Admin</strong>.</em></p>

                    <h6 class="fw-bold">A. Monthly Recap</h6>
                    <p>Buka <strong>Reports → Monthly Report</strong> untuk melihat rekap bulanan kehadiran, keterlambatan, cuti, dan lembur per karyawan.</p>

                    <h6 class="fw-bold mt-3">B. Executive Report (HR Administrator/Admin)</h6>
                    <p>Dashboard ringkasan eksekutif yang menampilkan overview perusahaan: total karyawan, tingkat kehadiran, distribusi departemen, dan tren bulanan.</p>

                    <h6 class="fw-bold mt-3">C. Export</h6>
                    <ul>
                        <li><strong>Export PDF</strong> — Unduh laporan per karyawan dalam format PDF.</li>
                        <li><strong>Export CSV</strong> — Unduh data mentah untuk analisis lebih lanjut di Excel.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    
    
    
    <section id="section-company-policy" class="kb-section">
        <div class="d-flex align-items-center mb-3 kb-section-header">
            <button class="btn btn-sm btn-outline-secondary me-2" onclick="showAllSections()"><i class="bi bi-arrow-left"></i></button>
            <h4 class="mb-0"><i class="bi bi-shield-check text-success"></i> Kebijakan & Peraturan Perusahaan</h4>
        </div>

        
        <?php if(isset($categories['company-policy'])): ?>
            <?php $__currentLoopData = $categories['company-policy']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card mb-3 kb-item" data-keywords="<?php echo e($article->keywords); ?>">
                    <div class="card-header bg-white d-flex align-items-center" style="cursor:pointer">
                        <div class="flex-grow-1" data-bs-toggle="collapse" data-bs-target="#article-<?php echo e($article->id); ?>">
                            <h5 class="mb-0">
                                <i class="bi bi-shield-check text-success me-2"></i> <?php echo e($article->title); ?>

                                <i class="bi bi-chevron-down float-end"></i>
                            </h5>
                        </div>
                        <?php if(Auth::user()->hasAccess('knowledge_base')): ?>
                            <div class="ms-3">
                                <a href="<?php echo e(route('knowledge-base.edit', $article->id)); ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('knowledge-base.destroy', $article->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="article-<?php echo e($article->id); ?>" class="collapse">
                        <div class="card-body">
                            <?php echo $article->content; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        
        <div class="card mb-3 kb-item" data-keywords="jam kerja operasional kode etik seragam pakaian aturan disiplin">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#policy-kerja">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-briefcase text-primary me-2"></i> Peraturan Kerja
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="policy-kerja" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">Jam Operasional</h6>
                    <table class="table table-bordered table-sm">
                        <tr><th width="220">Hari Kerja</th><td>Senin — Jumat</td></tr>
                        <tr><th>Jam Masuk</th><td>08:00 WIB</td></tr>
                        <tr><th>Jam Pulang</th><td>17:00 WIB</td></tr>
                        <tr><th>Toleransi Keterlambatan</th><td>15 menit (hingga 08:15)</td></tr>
                        <tr><th>Istirahat</th><td>12:00 — 13:00 WIB</td></tr>
                    </table>

                    <h6 class="fw-bold mt-4">Kode Etik</h6>
                    <ul>
                        <li>Menjaga kerahasiaan data perusahaan dan klien.</li>
                        <li>Bersikap profesional dan menghormati sesama rekan kerja.</li>
                        <li>Tidak menggunakan fasilitas kantor untuk kepentingan pribadi berlebihan.</li>
                        <li>Mematuhi kebijakan keamanan informasi dan penggunaan IT.</li>
                        <li>Melaporkan pelanggaran atau konflik kepentingan ke atasan/HR Administrator.</li>
                    </ul>

                    <h6 class="fw-bold mt-4">Kebijakan Pakaian</h6>
                    <ul>
                        <li><strong>Senin — Kamis:</strong> Business casual (kemeja, celana panjang).</li>
                        <li><strong>Jumat:</strong> Smart casual (polo shirt diperbolehkan).</li>
                        <li>Hindari sandal, kaos oblong, atau pakaian terlalu kasual.</li>
                    </ul>

                    <h6 class="fw-bold mt-4">Sanksi Pelanggaran</h6>
                    <ol>
                        <li><strong>Teguran Lisan</strong> — Pelanggaran ringan pertama kali.</li>
                        <li><strong>Surat Peringatan (SP) 1</strong> — Pelanggaran berulang.</li>
                        <li><strong>SP 2</strong> — Pelanggaran serius.</li>
                        <li><strong>SP 3 / PHK</strong> — Pelanggaran berat atau berulang.</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="cuti tahunan sakit melahirkan hak jatah annual leave menikah duka">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#policy-cuti">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-calendar2-week text-warning me-2"></i> Kebijakan Cuti
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="policy-cuti" class="collapse">
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light"><tr><th>Jenis Cuti</th><th>Durasi</th><th>Keterangan</th></tr></thead>
                        <tbody>
                            <tr><td>Cuti Tahunan</td><td>12 hari/tahun</td><td>Berlaku setelah 1 tahun kerja</td></tr>
                            <tr><td>Cuti Sakit</td><td>Sesuai surat dokter</td><td>Wajib lampirkan surat dokter</td></tr>
                            <tr><td>Cuti Melahirkan</td><td>3 bulan</td><td>Sesuai UU Ketenagakerjaan</td></tr>
                            <tr><td>Cuti Menikah</td><td>3 hari</td><td>Karyawan yang bersangkutan</td></tr>
                            <tr><td>Cuti Duka (orang tua/anak/pasangan)</td><td>3 hari</td><td>Hubungan langsung</td></tr>
                            <tr><td>Cuti Duka (saudara)</td><td>1 hari</td><td>Saudara kandung/mertua</td></tr>
                            <tr><td>Cuti Khitanan/Baptis Anak</td><td>2 hari</td><td>Anak karyawan</td></tr>
                            <tr><td>Izin Khusus</td><td>Sesuai kebutuhan</td><td>Persetujuan atasan</td></tr>
                        </tbody>
                    </table>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> Cuti tahunan yang tidak digunakan <strong>tidak dapat diuangkan</strong> dan hangus di akhir tahun (kecuali ada kebijakan khusus).
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="bpjs asuransi tunjangan benefit klaim medis reimbursement kesehatan thr transport makan">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#policy-benefit">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-heart-pulse text-danger me-2"></i> Manfaat & Tunjangan Karyawan
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="policy-benefit" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">Jaminan Sosial</h6>
                    <ul>
                        <li><strong>BPJS Kesehatan</strong> — Ditanggung sesuai ketentuan pemerintah.</li>
                        <li><strong>BPJS Ketenagakerjaan:</strong>
                            <ul>
                                <li>JHT (Jaminan Hari Tua)</li>
                                <li>JKK (Jaminan Kecelakaan Kerja)</li>
                                <li>JKM (Jaminan Kematian)</li>
                                <li>JP (Jaminan Pensiun)</li>
                            </ul>
                        </li>
                    </ul>
                    <h6 class="fw-bold mt-3">Tunjangan</h6>
                    <table class="table table-bordered table-sm">
                        <tr><td>Tunjangan Transport</td><td>Bulanan, bersama gaji</td></tr>
                        <tr><td>Tunjangan Makan</td><td>Per hari kerja</td></tr>
                        <tr><td>THR Administrator (Tunjangan Hari Raya)</td><td>1x gaji pokok, H-14 sebelum hari raya</td></tr>
                    </table>
                    <h6 class="fw-bold mt-3">Klaim Medis (Reimbursement)</h6>
                    <ol>
                        <li>Kumpulkan kwitansi dari fasilitas kesehatan.</li>
                        <li>Isi formulir klaim dan lampirkan dokumen.</li>
                        <li>Serahkan ke HR Administrator.</li>
                        <li>Penggantian masuk bersama gaji bulan berikutnya.</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="sop prosedur perjalanan dinas fasilitas kantor resign pengunduran diri serah terima aset">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#policy-sop">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-diagram-3 text-info me-2"></i> SOP Perusahaan
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="policy-sop" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">Perjalanan Dinas</h6>
                    <ol>
                        <li>Ajukan ke atasan → HR Administrator proses tiket/akomodasi.</li>
                        <li>Absensi via <span class="badge bg-info">WFA</span> selama perjalanan.</li>
                        <li>Laporan + bukti pengeluaran ke HR Administrator dalam 5 hari kerja.</li>
                    </ol>
                    <h6 class="fw-bold mt-3">Penggunaan Fasilitas</h6>
                    <ul>
                        <li>Perangkat kantor hanya untuk pekerjaan.</li>
                        <li>Peminjaman via <strong>Inventory → Requests</strong>.</li>
                        <li>Kerusakan wajib dilaporkan ke IT/GA.</li>
                    </ul>
                    <h6 class="fw-bold mt-3">Prosedur Resign</h6>
                    <ol>
                        <li>Beritahu atasan secara lisan.</li>
                        <li>Surat resign resmi <strong>minimal 30 hari</strong> sebelumnya.</li>
                        <li>Selesaikan serah terima pekerjaan.</li>
                        <li>Kembalikan semua aset perusahaan.</li>
                        <li>HR Administrator proses surat pengalaman kerja & hak-hak.</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="remote work wfh wfa kerja rumah hybrid fleksibel">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#policy-remote">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-house-door text-success me-2"></i> Kebijakan Remote Work (WFH/WFA)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="policy-remote" class="collapse">
                <div class="card-body">
                    <ul>
                        <li>Wajib mendapat <strong>persetujuan atasan</strong> sebelum WFH/WFA.</li>
                        <li>Absensi tetap dilakukan via aplikasi HRIS.</li>
                        <li>Harus bisa dihubungi selama jam kerja (08:00—17:00).</li>
                        <li>Koneksi internet stabil.</li>
                        <li>Laporan harian/progress wajib ke atasan.</li>
                        <li>Meeting online wajib dihadiri tepat waktu.</li>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="keamanan informasi data security password privasi rahasia akses">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#policy-security">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-shield-lock text-danger me-2"></i> Keamanan Informasi & Data
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="policy-security" class="collapse">
                <div class="card-body">
                    <ul>
                        <li><strong>Password</strong> — Gunakan password kuat (min. 8 karakter, kombinasi huruf/angka/simbol). Ganti secara berkala.</li>
                        <li><strong>Jangan bagikan akun</strong> — Akun HRIS bersifat pribadi dan tidak boleh digunakan orang lain.</li>
                        <li><strong>Logout</strong> — Selalu logout setelah selesai, terutama di perangkat bersama.</li>
                        <li><strong>Data karyawan</strong> — Informasi pribadi karyawan lain bersifat rahasia.</li>
                        <li><strong>Laporkan</strong> — Jika menemukan celah keamanan atau aktivitas mencurigakan, segera lapor ke IT.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    
    
    
    <section id="section-admin-guide" class="kb-section">
        <div class="d-flex align-items-center mb-3 kb-section-header">
            <button class="btn btn-sm btn-outline-secondary me-2" onclick="showAllSections()"><i class="bi bi-arrow-left"></i></button>
            <h4 class="mb-0"><i class="bi bi-gear text-warning"></i> Panduan Admin / HR Administrator</h4>
        </div>

        
        <?php if(isset($categories['admin-guide'])): ?>
            <?php $__currentLoopData = $categories['admin-guide']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card mb-3 kb-item" data-keywords="<?php echo e($article->keywords); ?>">
                    <div class="card-header bg-white d-flex align-items-center" style="cursor:pointer">
                        <div class="flex-grow-1" data-bs-toggle="collapse" data-bs-target="#article-<?php echo e($article->id); ?>">
                            <h5 class="mb-0">
                                <i class="bi bi-gear text-warning me-2"></i> <?php echo e($article->title); ?>

                                <i class="bi bi-chevron-down float-end"></i>
                            </h5>
                        </div>
                        <?php if(Auth::user()->hasAccess('knowledge_base')): ?>
                            <div class="ms-3">
                                <a href="<?php echo e(route('knowledge-base.edit', $article->id)); ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('knowledge-base.destroy', $article->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="article-<?php echo e($article->id); ?>" class="collapse">
                        <div class="card-body">
                            <?php echo $article->content; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <div class="alert alert-warning mb-3">
            <i class="bi bi-lock"></i> Panduan ini khusus untuk role <strong>HR Administrator</strong>, <strong>Super Admin</strong>, <strong>Manager / Unit Head</strong>, dan <strong>Super Admin</strong>.
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="employee karyawan tambah edit hapus crud data kelola manage">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-employee">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-people text-primary me-2"></i> Kelola Data Karyawan (Employees)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-employee" class="collapse">
                <div class="card-body">
                    <p>Menu: <strong>HR Administrator Management → Employees</strong></p>
                    <h6 class="fw-bold">Fitur:</h6>
                    <ul>
                        <li><strong>Tambah Karyawan Baru</strong> — Isi data lengkap (nama, NIK, departemen, jabatan, email, dll).</li>
                        <li><strong>Edit Data</strong> — Update informasi karyawan kapan saja.</li>
                        <li><strong>Lihat Detail</strong> — Klik nama karyawan untuk melihat profil lengkap.</li>
                        <li><strong>Hapus Karyawan</strong> — Nonaktifkan atau hapus karyawan yang resign.</li>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="approval persetujuan update data karyawan verifikasi konfirmasi">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-approvals">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-person-check text-success me-2"></i> Persetujuan Update Data (Employee Approvals)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-approvals" class="collapse">
                <div class="card-body">
                    <p>Menu: <strong>HR Administrator Management → Update Approvals</strong></p>
                    <p>Saat karyawan mengubah data kritis (nama, rekening, dll), perubahan masuk ke antrian persetujuan.</p>
                    <ol>
                        <li>Buka halaman <strong>Update Approvals</strong>.</li>
                        <li>Review perubahan yang diajukan.</li>
                        <li>Klik <strong>Approve</strong> atau <strong>Reject</strong>.</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="departemen department divisi role jabatan hak akses organisasi org chart struktur">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-dept">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-diagram-3 text-info me-2"></i> Departemen, Roles & Org Chart
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-dept" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">A. Kelola Departemen</h6>
                    <p>Menu: <strong>HR Administrator Management → Departments</strong></p>
                    <ul>
                        <li>Tambah, edit, hapus departemen.</li>
                        <li>Lihat <strong>Org Chart</strong> — struktur organisasi visual perusahaan.</li>
                    </ul>

                    <h6 class="fw-bold mt-3">B. Kelola Roles</h6>
                    <p>Menu: <strong>HR Administrator Management → Roles</strong></p>
                    <p>Roles menentukan hak akses karyawan di sistem:</p>
                    <table class="table table-bordered table-sm">
                        <thead class="table-light"><tr><th>Role</th><th>Akses</th></tr></thead>
                        <tbody>
                            <tr><td><span class="badge bg-dark">Super Admin</span></td><td>Akses penuh ke semua fitur</td></tr>
                            <tr><td><span class="badge bg-danger">HR Administrator</span></td><td>Kelola karyawan, payroll, cuti, inventory, surat</td></tr>
                            <tr><td><span class="badge bg-warning text-dark">Super Admin</span></td><td>Sama seperti HR Administrator</td></tr>
                            <tr><td><span class="badge bg-primary">Manager / Unit Head</span></td><td>Lihat tim, approve KPI/cuti, buat task</td></tr>
                            <tr><td><span class="badge bg-info">Employee</span></td><td>Akses dasar: absensi, task, profil</td></tr>
                            <tr><td><span class="badge bg-secondary">Employee</span></td><td>Akses dasar: absensi, task, profil</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="absensi manual hr input kehadiran koreksi presensi admin">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-presence">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-clock text-warning me-2"></i> Input Absensi Manual (HR Administrator)
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-presence" class="collapse">
                <div class="card-body">
                    <p>HR Administrator dapat menginput kehadiran karyawan secara manual:</p>
                    <ol>
                        <li>Buka <strong>Presences → Create New Presence</strong> (sebagai HR Administrator).</li>
                        <li>Pilih <strong>Employee</strong> dari dropdown.</li>
                        <li>Isi <strong>Check In</strong> dan <strong>Check Out</strong>.</li>
                        <li>Pilih <strong>Status</strong> (Present, Absent, Leave).</li>
                        <li>Klik <strong>"Save"</strong>.</li>
                    </ol>
                    <p class="text-muted">Gunakan fitur ini ketika karyawan lupa absen atau ada masalah teknis.</p>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="payroll gaji kelola input salary buat proses hitung">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-payroll">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-currency-dollar text-success me-2"></i> Kelola Payroll
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-payroll" class="collapse">
                <div class="card-body">
                    <p>Menu: <strong>Payroll & KPI → Payrolls</strong></p>
                    <ol>
                        <li><strong>Buat Payroll Baru</strong> — Pilih karyawan dan periode.</li>
                        <li><strong>Input Komponen</strong> — Gaji pokok, tunjangan, potongan.</li>
                        <li><strong>Review</strong> — Periksa semua data sebelum finalize.</li>
                        <li><strong>Edit</strong> — Koreksi data jika ada kesalahan.</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="task assign buat tugaskan karyawan manager hr delegasi">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-task">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-list-task text-danger me-2"></i> Buat & Assign Task
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-task" class="collapse">
                <div class="card-body">
                    <p>Menu: <strong>HR Administrator Management → Tasks</strong></p>
                    <ol>
                        <li>Klik <strong>"Create New Task"</strong>.</li>
                        <li>Isi: Judul, Deskripsi, Assign ke karyawan, Deadline, Status awal.</li>
                        <li>Klik <strong>"Save"</strong>.</li>
                    </ol>
                    <p class="fw-bold mt-2">Siapa bisa assign ke siapa?</p>
                    <ul>
                        <li><strong>HR Administrator / Super Admin / Super Admin</strong> → semua karyawan aktif.</li>
                        <li><strong>Manager / Unit Head</strong> → karyawan di departemennya.</li>
                        <li><strong>Supervisor</strong> → bawahan langsung saja.</li>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="inventory inventaris kategori barang kelola stok approve reject request">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-inventory">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-boxes text-info me-2"></i> Kelola Inventory
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-inventory" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">A. Kategori Inventaris</h6>
                    <p>Menu: <strong>Inventory → Categories</strong> — Buat kategori (Elektronik, Furniture, ATK, dll).</p>

                    <h6 class="fw-bold mt-3">B. Data Inventaris</h6>
                    <p>Menu: <strong>Inventory → Inventories</strong> — Tambah barang baru, update stok, edit/hapus.</p>

                    <h6 class="fw-bold mt-3">C. Approve/Reject Permintaan</h6>
                    <ol>
                        <li>Buka <strong>Inventory → Requests</strong>.</li>
                        <li>Lihat permintaan masuk.</li>
                        <li>Klik <strong>Approve</strong> atau <strong>Reject</strong>.</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="letter surat template konfigurasi arsip approve reject kelola admin">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-letters">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-envelope-paper text-primary me-2"></i> Kelola Surat, Template & Konfigurasi
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-letters" class="collapse">
                <div class="card-body">
                    <h6 class="fw-bold">A. Template Surat</h6>
                    <p>Menu: <strong>Letters → Templates</strong> — Buat/edit template surat (format, header, footer, variabel).</p>

                    <h6 class="fw-bold mt-3">B. Konfigurasi Surat</h6>
                    <p>Menu: <strong>Letters → Configurations</strong> — Atur penomoran surat, tanda tangan default, logo.</p>

                    <h6 class="fw-bold mt-3">C. Approve/Reject Surat</h6>
                    <p>Review surat yang di-submit, lalu Approve atau Reject dengan alasan.</p>

                    <h6 class="fw-bold mt-3">D. Arsip Surat</h6>
                    <p>Menu: <strong>Letters → Archives</strong> — Akses semua surat yang sudah diproses.</p>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 kb-item" data-keywords="fingerprint reset browser perangkat device baru ganti hapus">
            <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#admin-fingerprint">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-fingerprint text-secondary me-2"></i> Reset Fingerprint Browser Karyawan
                    <i class="bi bi-chevron-down ms-auto"></i>
                </h5>
            </div>
            <div id="admin-fingerprint" class="collapse">
                <div class="card-body">
                    <p>Jika karyawan ganti perangkat/browser dan tidak bisa absen:</p>
                    <ol>
                        <li>Buka <strong>HR Administrator Management → Employees</strong>.</li>
                        <li>Cari dan buka profil karyawan tersebut.</li>
                        <li>Pada bagian data login/fingerprint, <strong>kosongkan</strong> field <code>browser_fingerprint_mobile</code> atau <code>browser_fingerprint_desktop</code>.</li>
                        <li>Save. Fingerprint baru akan otomatis terdaftar saat absensi berikutnya.</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    
    
    
    <section id="section-faq" class="kb-section">
        <div class="d-flex align-items-center mb-3 kb-section-header">
            <button class="btn btn-sm btn-outline-secondary me-2" onclick="showAllSections()"><i class="bi bi-arrow-left"></i></button>
            <h4 class="mb-0"><i class="bi bi-question-circle text-info"></i> Frequently Asked Questions (FAQ)</h4>
        </div>

        <?php
        $faqs = [
            ['q' => 'Bagaimana jika saya lupa absen / clock-in?', 'a' => 'Hubungi <strong>HR Administrator</strong> sesegera mungkin. HR Administrator dapat menginput kehadiran manual melalui sistem. Lampirkan bukti kehadiran (screenshot chat/bukti pekerjaan).', 'kw' => 'lupa absen presensi kehadiran'],
            ['q' => 'Fingerprint browser tidak cocok / ganti perangkat?', 'a' => 'Hubungi <strong>HR Administrator atau IT Admin</strong> untuk mereset fingerprint di sistem. Setelah di-reset, fingerprint baru otomatis terdaftar saat absensi berikutnya.', 'kw' => 'fingerprint browser ganti perangkat device tidak cocok'],
            ['q' => 'GPS menunjukkan lokasi yang salah / di luar radius?', 'a' => '<ul><li>Pastikan GPS aktif di perangkat.</li><li>Izinkan akses lokasi di browser.</li><li>Klik <strong>"Refresh GPS"</strong>.</li><li>Coba keluar ruangan untuk sinyal lebih baik.</li><li>Jika masih gagal, hubungi HR Administrator untuk input manual.</li></ul>', 'kw' => 'gps lokasi salah jauh radius kantor tidak akurat'],
            ['q' => 'Verifikasi wajah gagal / kamera tidak terdeteksi?', 'a' => '<ul><li>Izinkan akses kamera di browser.</li><li>Pastikan pencahayaan cukup.</li><li>Lepas masker/kacamata hitam.</li><li>Tunggu hingga <span class="badge bg-success">✅ Wajah Terverifikasi</span>.</li><li>Jika gagal, refresh halaman atau ganti browser.</li></ul>', 'kw' => 'face wajah kamera verifikasi gagal tidak terdeteksi'],
            ['q' => 'WiFi SSID tidak ada di daftar?', 'a' => '<ul><li><strong>WFO:</strong> Harus terhubung ke WiFi kantor (<em>UNPAM VIKTOR, Serhan 2, Serhan, S53s</em>). Jika bermasalah, hubungi IT.</li><li><strong>WFH/WFA:</strong> Pilih dari daftar atau pilih <strong>"Other (Input Manual)"</strong> dan ketik manual.</li></ul>', 'kw' => 'wifi ssid tidak tersedia other kantor koneksi'],
            ['q' => 'Bagaimana cara melihat sisa cuti saya?', 'a' => 'Buka <strong>HR Administrator Management → Leave Requests</strong>. Informasi sisa kuota cuti ditampilkan di bagian atas halaman.', 'kw' => 'cuti sisa berapa jatah kuota annual leave'],
            ['q' => 'Slip gaji tidak sesuai / ada potongan yang salah?', 'a' => 'Hubungi <strong>HR Administrator/Finance</strong> dengan detail: periode gaji, item yang tidak sesuai, dan bukti pendukung. Tim HR Administrator akan verifikasi dan koreksi.', 'kw' => 'gaji salah potongan tidak sesuai slip payroll'],
            ['q' => 'Saya lupa password / tidak bisa login?', 'a' => '<ol><li>Klik <strong>"Forgot Password"</strong> di halaman login.</li><li>Masukkan email terdaftar.</li><li>Cek inbox untuk link reset.</li><li>Jika tidak menerima email, hubungi <strong>IT Admin</strong>.</li></ol>', 'kw' => 'password lupa reset akun login tidak bisa masuk'],
            ['q' => 'Bagaimana cara mengajukan surat keterangan kerja?', 'a' => 'Buka <strong>Letters → Create New Letter</strong>, pilih template "Surat Keterangan Kerja", isi data, lalu Submit. Tunggu persetujuan HR Administrator, kemudian cetak/download.', 'kw' => 'surat keterangan kerja paklaring referensi'],
            ['q' => 'Bagaimana cara meminjam barang inventaris?', 'a' => 'Buka <strong>Inventory → Requests → Create New Request</strong>. Pilih barang, isi jumlah dan alasan, lalu Submit. Tunggu persetujuan HR Administrator.', 'kw' => 'pinjam barang inventaris peralatan kantor'],
            ['q' => 'Task saya tidak muncul di daftar?', 'a' => 'Pastikan Anda login dengan akun yang benar. Task hanya muncul untuk karyawan yang di-assign. Jika yakin seharusnya muncul, hubungi atasan atau HR Administrator.', 'kw' => 'task tidak muncul hilang kosong daftar'],
            ['q' => 'Bagaimana cara submit KPI?', 'a' => 'Buka <strong>Payroll & KPI → KPI Dashboard</strong>, lalu klik <strong>"Submit KPI"</strong>. Isi self-assessment Anda dan tunggu persetujuan atasan.', 'kw' => 'kpi submit kirim penilaian kinerja'],
            ['q' => 'Apakah data saya aman di sistem HRIS?', 'a' => 'Ya. Sistem menggunakan enkripsi dan hak akses berbasis role. Data pribadi hanya bisa diakses oleh Anda sendiri dan HR Administrator. Pastikan selalu logout setelah selesai.', 'kw' => 'data aman keamanan privasi rahasia'],
            ['q' => 'Siapa yang harus dihubungi jika butuh bantuan?', 'a' => '<table class="table table-bordered table-sm mb-0"><tr><th>Masalah HR Administrator/Umum</th><td>Tim HR Administrator</td></tr><tr><th>Masalah Teknis</th><td>IT Admin / Super Admin</td></tr><tr><th>Masalah Keuangan</th><td>Finance / HR Administrator</td></tr><tr><th>Keluhan/Saran</th><td>Atasan langsung / HR Administrator</td></tr></table>', 'kw' => 'kontak hubungi bantuan help support admin'],
        ];
        ?>

        <div class="accordion" id="faqAccordion">
            
            <?php if(isset($categories['faq'])): ?>
                <?php $__currentLoopData = $categories['faq']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mb-2 kb-item" data-keywords="<?php echo e($article->keywords); ?>">
                        <div class="card-header bg-white d-flex align-items-center" style="cursor:pointer">
                            <div class="flex-grow-1" data-bs-toggle="collapse" data-bs-target="#article-<?php echo e($article->id); ?>">
                                <h6 class="mb-0">
                                    <i class="bi bi-patch-question text-info me-2"></i> <?php echo e($article->title); ?>

                                    <i class="bi bi-chevron-down float-end"></i>
                                </h6>
                            </div>
                            <?php if(Auth::user()->hasAccess('knowledge_base')): ?>
                                <div class="ms-3 text-nowrap">
                                    <a href="<?php echo e(route('knowledge-base.edit', $article->id)); ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                                    <form action="<?php echo e(route('knowledge-base.destroy', $article->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div id="article-<?php echo e($article->id); ?>" class="collapse">
                            <div class="card-body"> <?php echo $article->content; ?> </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <div class="alert alert-info py-2 small mb-3">
                <i class="bi bi-info-circle me-1"></i> Pertanyaan umum lainnya (Hardcoded)
            </div>
            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card mb-2 kb-item" data-keywords="<?php echo e($faq['kw']); ?>">
                <div class="card-header bg-white" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#faq-<?php echo e($i); ?>">
                    <h6 class="mb-0"><i class="bi bi-patch-question text-primary me-2"></i> <?php echo e($faq['q']); ?></h6>
                </div>
                <div id="faq-<?php echo e($i); ?>" class="collapse">
                    <div class="card-body"><?php echo $faq['a']; ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

</div>

<style>
.kb-nav-card:hover { transform: translateY(-4px); transition: transform 0.2s ease; box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.kb-item { border-radius: 8px; transition: box-shadow 0.2s ease; }
.kb-item:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
.kb-item .card-header h5, .kb-item .card-header h6 { font-size: 0.95rem; }
.kb-item .card-header .bi-chevron-down { transition: transform 0.3s ease; font-size: 0.8rem; }
.kb-item .card-header[aria-expanded="true"] .bi-chevron-down,
.kb-item .card-header:not(.collapsed) .bi-chevron-down { transform: rotate(180deg); }
.kb-section { animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
function showSection(section) {
    document.getElementById('kb-nav-cards').style.display = 'none';
    document.querySelectorAll('.kb-section').forEach(el => el.style.display = 'none');
    document.getElementById('section-' + section).style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
function showAllSections() {
    document.getElementById('kb-nav-cards').style.display = 'flex';
    document.querySelectorAll('.kb-section').forEach(el => el.style.display = 'none');
    document.getElementById('kb-search').value = '';
    document.getElementById('search-count').style.display = 'none';
    document.querySelectorAll('.kb-item').forEach(item => {
        item.style.display = '';
        item.querySelectorAll('.collapse').forEach(c => c.classList.remove('show'));
    });
    document.querySelectorAll('.kb-section-header').forEach(el => el.style.display = '');
}
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.kb-section').forEach(el => el.style.display = 'none');
    const searchInput = document.getElementById('kb-search');
    const searchCount = document.getElementById('search-count');
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        if (query === '') { showAllSections(); return; }
        document.getElementById('kb-nav-cards').style.display = 'none';
        document.querySelectorAll('.kb-section').forEach(el => el.style.display = 'block');
        document.querySelectorAll('.kb-section-header').forEach(el => el.style.display = 'none');
        let count = 0;
        document.querySelectorAll('.kb-item').forEach(item => {
            const keywords = (item.dataset.keywords || '').toLowerCase();
            const text = item.textContent.toLowerCase();
            const match = keywords.includes(query) || text.includes(query);
            item.style.display = match ? '' : 'none';
            if (match) { count++; item.querySelectorAll('.collapse').forEach(c => c.classList.add('show')); }
            else { item.querySelectorAll('.collapse').forEach(c => c.classList.remove('show')); }
        });
        searchCount.textContent = count + ' hasil';
        searchCount.style.display = 'flex';
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/knowledge-base/index.blade.php ENDPATH**/ ?>