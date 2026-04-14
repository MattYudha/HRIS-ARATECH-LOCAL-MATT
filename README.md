# HRIS Aratech - Enterprise Human Resource & Financial System

![Enterprise Status](https://img.shields.io/badge/Status-Enterprise--Grade-blue?style=for-the-badge&logo=laravel)
![Build Status](https://img.shields.io/badge/Build-CI%2FCD--Active-success?style=for-the-badge&logo=github-actions)
![Docker](https://img.shields.io/badge/Environment-Docker--Ready-0db7ed?style=for-the-badge&logo=docker)

## 🏢 Overview

**HRIS Aratech** adalah solusi manajemen sumber daya manusia dan keuangan terpadu yang dirancang untuk skala enterprise. Sistem ini mengintegrasikan seluruh aspek operasional perusahaan—mulai dari absensi karyawan dan penggajian hingga pembukuan keuangan yang patuh pada regulasi perpajakan (Coretax Ready).

Dibangun dengan arsitektur yang tangguh menggunakan Laravel, sistem ini menjamin keamanan data, transparansi audit, dan kemudahan skalabilitas melalui kontainerisasi Docker.

---

## 🚀 Fitur Utama (Core Modules)

### 📈 Financial & Accounting (Coretax Ready)
*   **General Ledger & Cash Book:** Pencatatan transaksi harian otomatis dengan penghitungan saldo berjalan (*running balance*).
*   **Tax Compliance:** Perhitungan otomatis PPN 11%, PPh 21, PPh 23, dan PPh 4(2) yang adaptif terhadap regulasi terbaru.
*   **Claims & Reimbursement:** Sistem pengajuan biaya karyawan dengan alur persetujuan admin dan integrasi jurnal otomatis.
*   **Secure Document Management:** Penyimpanan bukti transaksi di storage privat dengan akses terbatas untuk auditabilitas tinggi.
*   **Quick-Add Workflow:** Menambahkan vendor, bank, atau bagan akun (CoA) secara instan tanpa menghentikan alur kerja.

### 👥 Human Capital Management
*   **Employee Lifecycle:** Database karyawan lengkap, sistem persetujuan update profil, dan KPI Performance monitoring.
*   **Attendance & Payroll:** Manajemen absensi (Presences) yang terintegrasi langsung dengan modul penggajian (Payrolls) otomatis.
*   **Leave & Duty:** Pengajuan cuti dan izin kerja dengan sistem kalender terpadu.

### 📦 Logistics & Operations
*   **Inventory Control:** Monitoring stok barang, log penggunaan, dan sistem *procurement* (pengadaan) barang.
*   **Letter & Archive:** Manajemen persuratan digital, template surat dinas, dan pengarsipan berbasis cloud.
*   **Task & Collaboration:** Manajemen tugas tim dengan fitur komentar dan pelacakan progres secara real-time.

---

## 🛠️ Technology Stack

| Component | Technology |
|---|---|
| **Backend** | Laravel 10 (PHP 8.2+) |
| **Frontend** | Blade Engine, Vanilla JS, Bootstrap 5 / Modern CSS |
| **Database** | MySQL 8.0 |
| **Storage** | Secure Local Private Storage |
| **Infrastructure** | Docker & Docker Compose |
| **CI/CD** | GitHub Actions with SSH-based Zero Downtime Deploy |

---

## ⚙️ Instalasi (Local Development)

### Menggunakan Docker (Rekomendasi)
Sistem sudah terkonfigurasi sepenuhnya untuk dijalankan menggunakan Docker Compose.

1.  **Clone Repository:**
    ```bash
    git clone https://github.com/MattYudha/HRIS-ARATECH-LOCAL-MATT.git
    cd HRIS-ARATECH-LOCAL-MATT
    ```

2.  **Environment Setup:**
    ```bash
    cp .env.example .env
    ```

3.  **Build & Run:**
    ```bash
    docker-compose up -d --build
    ```

4.  **Database Migration & Seeding:**
    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

Sistem sekarang dapat diakses di `http://localhost:8000`.

---

## 🛡️ Security & Integrity
*   **Audit Trail:** Setiap aktivitas perubahan data krusial dicatat dalam log audit sistem.
*   **Role-Based Access Control (RBAC):** Pembatasan hak akses yang ketat antara Admin, Keuangan, dan Karyawan Umum.
*   **Encrypted Storage:** Seluruh dokumen sensitif disimpan di luar direktori publik untuk mencegah pengunduhan ilegal.

---

## 📄 License
Project ini bersifat **Proprietary** dan dikembangkan khusus untuk **Aratech Software Solution**. Seluruh hak cipta dilindungi undang-undang.

---
*Developed with ❤️ by Aratech Engineering Team.*
