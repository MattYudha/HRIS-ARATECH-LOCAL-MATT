# 📘 Playbook HRIS Aratech

### PT Aratech Nusantara Indonesia

**Versi:** 1.0 &nbsp;|&nbsp; **Tanggal:** 7 Maret 2026 &nbsp;|&nbsp; **URL:** `https://hris.aratechnology.id`

---

## Daftar Isi

1. [Login & Autentikasi](#1-login--autentikasi)
2. [Dashboard](#2-dashboard)
3. [HR Management](#3-hr-management)
    - 3.1 [Employees](#31-employees)
    - 3.2 [Update Approvals](#32-update-approvals)
    - 3.3 [Departments](#33-departments)
    - 3.4 [Roles](#34-roles)
    - 3.5 [Tasks](#35-tasks)
    - 3.6 [Leave Requests](#36-leave-requests)
    - 3.7 [Incidents & Awards](#37-incidents--awards)
4. [Payroll & KPI](#4-payroll--kpi)
    - 4.1 [Payrolls](#41-payrolls)
    - 4.2 [KPI Dashboard](#42-kpi-dashboard)
    - 4.3 [Team KPI](#43-team-kpi)
    - 4.4 [Department KPI](#44-department-kpi)
    - 4.5 [Pending Approvals](#45-pending-approvals-kpi)
5. [Presences (Kehadiran)](#5-presences-kehadiran)
6. [Inventory](#6-inventory)
    - 6.1 [Categories](#61-categories)
    - 6.2 [Inventories](#62-inventories)
    - 6.3 [Usage Logs](#63-usage-logs)
    - 6.4 [Requests](#64-requests)
7. [Letters (Surat Menyurat)](#7-letters-surat-menyurat)
    - 7.1 [Letters](#71-letters)
    - 7.2 [Templates](#72-templates)
    - 7.3 [Configurations](#73-configurations)
    - 7.4 [Archives](#74-archives)
    - 7.5 [Signature Logs](#75-signature-logs)
8. [Digital Signature (Tanda Tangan Digital)](#8-digital-signature)
9. [Reports & Reporting](#9-reports--reporting)
    - 9.1 [Executive Report](#91-executive-report)
    - 9.2 [Monthly Report](#92-monthly-report)
10. [Personal](#10-personal)
    - 10.1 [My Profile](#101-my-profile)
    - 10.2 [Knowledge Base](#102-knowledge-base)
11. [Role & Hak Akses](#11-role--hak-akses)

---

## 1. Login & Autentikasi

| Item                 | Detail                          |
| -------------------- | ------------------------------- |
| **URL**              | `https://hris.aratechnology.id` |
| **Framework**        | Laravel Breeze (Session-based)  |
| **Session Lifetime** | 120 menit                       |

### Langkah Login

1. Buka URL aplikasi → tampil halaman login
2. Masukkan **Email** dan **Password**
3. Klik tombol **Login**
4. Sistem memvalidasi kredensial dan menyimpan `role` serta `employee_id` ke session
5. Redirect ke halaman **Dashboard**

### Fitur Tambahan

- **Forgot Password** — Reset password via email
- **Dark/Light Mode Toggle** — Dapat diubah dari ikon di header, preferensi tersimpan di `localStorage`

> **💡 Tip:** Tema (dark/light) akan dipertahankan antar sesi melalui `localStorage`.

---

## 2. Dashboard

| Item           | Detail                      |
| -------------- | --------------------------- |
| **Route**      | `GET /dashboard`            |
| **Controller** | `DashboardController@index` |
| **Akses**      | Semua user yang sudah login |

### Konten Dashboard

Dashboard menampilkan **ringkasan real-time** dari berbagai modul:

| Widget               | Deskripsi                                |
| -------------------- | ---------------------------------------- |
| **Total Karyawan**   | Jumlah karyawan aktif                    |
| **Total Departemen** | Jumlah departemen aktif                  |
| **Total Surat**      | Jumlah surat yang dibuat                 |
| **Total Tugas**      | Total tugas yang tercatat                |
| **Grafik Kehadiran** | Chart 12 bulan terakhir (bar chart)      |
| **Grafik Payroll**   | Chart pengeluaran gaji 12 bulan terakhir |
| **Widget KPI**       | Trend KPI dan insights kinerja           |

### Logika Data Berdasarkan Role

| Role                            | Data yang Ditampilkan           |
| ------------------------------- | ------------------------------- |
| **HR / Power User / Developer** | Statistik global semua karyawan |
| **Manager**                     | Statistik departemen sendiri    |
| **Employee / Sales**            | Statistik personal              |

> **📝 Note:** Dashboard menggunakan AJAX endpoint `GET /dashboard/presence` untuk data chart kehadiran dalam format JSON.

---

## 3. HR Management

Kelompok menu ini mengelola inti data SDM organisasi.

### 3.1 Employees

| Item           | Detail                                                     |
| -------------- | ---------------------------------------------------------- |
| **Route**      | `resource: /employees`                                     |
| **Controller** | `EmployeeController`                                       |
| **Akses**      | HR, Power User, Manager, Developer, Common Employee, Sales |

#### Fitur CRUD Karyawan

| Aksi       | Deskripsi                                                         |
| ---------- | ----------------------------------------------------------------- |
| **Index**  | Daftar karyawan dengan DataTables (search, sort, pagination)      |
| **Create** | Form tambah karyawan baru — biodata, departemen, role, gaji, foto |
| **Show**   | Detail lengkap karyawan termasuk dokumen, keluarga, mutasi        |
| **Edit**   | Update data karyawan dengan tracking mutasi otomatis              |
| **Delete** | Hapus data karyawan (hanya HR/Power User)                         |

#### Data yang Dikelola

```
├── Biodata (fullname, email, phone, alamat, tempat/tanggal lahir)
├── Status Kepegawaian (active, inactive, on_leave, terminated)
├── Department & Role assignment
├── Gaji (salary)
├── NIK (unique)
├── NPWP
├── Pendidikan Terakhir (education_level)
├── Tipe Identitas (KTP/SIM/Passport)
├── Data Keluarga (EmployeeFamily)
├── Dokumen Karyawan (EmployeeDocument)
├── Riwayat Mutasi (EmployeeMutation)
└── Bank Account
```

#### Tracking Mutasi Otomatis

Saat karyawan di-update, sistem otomatis mendeteksi dan mencatat jenis mutasi:

- **Promosi** — Role berubah ke posisi lebih tinggi
- **Demosi** — Role berubah ke posisi lebih rendah
- **Rotasi** — Perpindahan lateral antar departemen
- **Kenaikan Gaji / Penurunan Gaji**

#### Upload Dokumen

- Route: `POST /employees/{employee}/documents`
- Akses: HR, Power User
- Mendukung upload multiple file per karyawan

---

### 3.2 Update Approvals

| Item           | Detail                             |
| -------------- | ---------------------------------- |
| **Route**      | `GET /employee-approvals`          |
| **Controller** | `EmployeeUpdateApprovalController` |
| **Akses**      | HR, Power User                     |

#### Alur Kerja

1. Karyawan mengajukan perubahan data pribadi
2. Perubahan masuk ke antrian **approval**
3. HR/Power User mereview perubahan di halaman ini
4. Aksi yang tersedia:
    - ✅ **Approve** — `POST /employee-approvals/{id}/approve`
    - ❌ **Reject** — `POST /employee-approvals/{id}/reject`

---

### 3.3 Departments

| Item                | Detail                                   |
| ------------------- | ---------------------------------------- |
| **Route**           | `resource: /departments`                 |
| **Controller**      | `DepartmentController`                   |
| **Akses CRUD**      | HR, Power User                           |
| **Akses Org Chart** | HR, Power User, Manager, Common Employee |

#### Fitur

| Fitur                  | Deskripsi                                                        |
| ---------------------- | ---------------------------------------------------------------- |
| **DataTables View**    | Daftar departemen dengan nama manager, jumlah karyawan, status   |
| **Hierarki**           | Support `parent_id` untuk struktur departemen berjenjang         |
| **Manager Assignment** | Setiap departemen bisa di-assign seorang manager                 |
| **Org Chart**          | Visualisasi struktur organisasi via `GET /departments/org-chart` |
| **Safe Deletion**      | Tidak bisa hapus departemen yang masih memiliki karyawan         |

---

### 3.4 Roles

| Item           | Detail             |
| -------------- | ------------------ |
| **Route**      | `resource: /roles` |
| **Controller** | `RoleController`   |
| **Akses**      | HR, Power User     |

#### Roles yang Tersedia

| Role                | Deskripsi                                   |
| ------------------- | ------------------------------------------- |
| **Power User**      | Akses penuh ke semua modul                  |
| **HR**              | Akses penuh ke semua modul HR               |
| **Manager**         | Akses ke data departemen sendiri + approval |
| **Developer**       | Akses teknis + beberapa modul HR            |
| **Common Employee** | Akses terbatas ke data pribadi              |
| **Sales**           | Akses terbatas ke data pribadi              |

---

### 3.5 Tasks

| Item           | Detail                                    |
| -------------- | ----------------------------------------- |
| **Route**      | `resource: /tasks`                        |
| **Controller** | `TaskController`, `TaskCommentController` |
| **Akses**      | Semua role                                |

#### Fitur Task Management

| Fitur                | Deskripsi                                                |
| -------------------- | -------------------------------------------------------- |
| **Create Task**      | Buat tugas baru, assign ke karyawan                      |
| **DataTables View**  | Daftar tugas dengan filter dan sorting                   |
| **Status Tracking**  | Ubah status: `pending` ↔ `done`                          |
| **Komentar**         | Tambah komentar di setiap task untuk monitoring progress |
| **Assignable Logic** | Hanya bisa assign ke bawahan sesuai hierarki             |

#### Status Task Flow

```
  [Created / Pending] ---> Mark as Done ---> [Done]
  [Done] ---> Revert to Pending ---> [Created / Pending]
```

#### Task Comments

- **Tambah**: `POST /tasks/{task}/comments`
- **Hapus**: `DELETE /tasks/comments/{comment}`
- Setiap user dapat menambah komentar untuk tracking progress

---

### 3.6 Leave Requests

| Item           | Detail                      |
| -------------- | --------------------------- |
| **Route**      | `resource: /leave-requests` |
| **Controller** | `LeaveRequestController`    |
| **Akses**      | Semua role                  |

#### Alur Pengajuan Cuti

```
  Karyawan Ajukan Cuti
         ↓
  Status: Pending
         ↓
  HR/Manager Review
      ↙       ↘
Confirmed ✅   Rejected ❌
     ↓
Saldo Cuti Diupdate Otomatis
```

#### Fitur Detail

| Fitur             | Deskripsi                                               |
| ----------------- | ------------------------------------------------------- |
| **Create**        | Form pengajuan — jenis cuti, tanggal mulai/selesai      |
| **Data Scoping**  | HR: lihat semua, Manager: departemen, Employee: sendiri |
| **Confirm**       | Approve cuti + otomatis kurangi saldo (`LeaveBalance`)  |
| **Reject**        | Tolak pengajuan cuti                                    |
| **Leave Balance** | Tracking saldo cuti per jenis (annual, sick, dll.)      |

---

### 3.7 Incidents & Awards

| Item           | Detail                 |
| -------------- | ---------------------- |
| **Route**      | `resource: /incidents` |
| **Controller** | `IncidentController`   |
| **Akses**      | Semua role             |

#### Data Incident

| Field              | Deskripsi                                         |
| ------------------ | ------------------------------------------------- |
| **Type**           | Jenis insiden/penghargaan (free text)             |
| **Severity**       | `low`, `medium`, `high`, `critical`               |
| **Status**         | `pending`, `investigating`, `resolved`, `closed`  |
| **Description**    | Deskripsi detail kejadian                         |
| **Action Taken**   | Tindakan yang diambil                             |
| **Reported By**    | Auto-fill oleh user yang login                    |
| **Resolved By/At** | Otomatis terisi saat status berubah ke `resolved` |

> **📝 Note:** Halaman index mendukung filter berdasarkan tipe insiden.

---

## 4. Payroll & KPI

### 4.1 Payrolls

| Item           | Detail                              |
| -------------- | ----------------------------------- |
| **Route**      | `resource: /payrolls`               |
| **Controller** | `PayrollsController`                |
| **Akses CRUD** | Developer, HR, Power User           |
| **Akses View** | Semua role (data terbatas per role) |

#### Fitur Payroll

| Fitur                    | Deskripsi                                                   |
| ------------------------ | ----------------------------------------------------------- |
| **Create Payroll**       | Hitung gaji berdasarkan data kehadiran & info karyawan      |
| **AJAX Attendance Data** | `GET /payrolls/attendance-data` — auto-fetch data kehadiran |
| **AJAX Employee Data**   | `GET /payrolls/employee-data` — auto-fetch data gaji pokok  |
| **DataTables View**      | Daftar payroll dengan sorting & search                      |
| **Slip Gaji Digital**    | Lihat detail payroll per karyawan                           |

#### Komponen Payroll

```
Gaji Bersih = Gaji Pokok + Tunjangan - Potongan
├── Gaji Pokok (dari data Employee)
├── Tunjangan (allowances)
├── Potongan (deductions)
├── Overtime (lembur)
└── Berdasarkan data Presence bulan berjalan
```

---

### 4.2 KPI Dashboard

| Item           | Detail                    |
| -------------- | ------------------------- |
| **Route**      | `GET /kpi/dashboard`      |
| **Controller** | `KPIController@dashboard` |
| **Akses**      | Semua user                |

Menampilkan **KPI personal** user yang login:

- Metrik KPI berdasarkan kategori (`metric_category` & `metric_key`)
- Skor dan status per metrik
- Chart trend performa waktu ke waktu
- Summary insights

---

### 4.3 Team KPI

| Item      | Detail                  |
| --------- | ----------------------- |
| **Route** | `GET /kpi/team`         |
| **Akses** | Manager, HR, Power User |

Menampilkan **performa tim** (bawahan langsung):

- Tabel perbandingan KPI antar anggota tim
- Rata-rata skor tim

---

### 4.4 Department KPI

| Item      | Detail                  |
| --------- | ----------------------- |
| **Route** | `GET /kpi/department`   |
| **Akses** | Manager, HR, Power User |

Ringkasan **KPI per departemen**:

- Rata-rata skor departemen
- Perbandingan antar departemen

---

### 4.5 Pending Approvals (KPI)

| Item      | Detail                  |
| --------- | ----------------------- |
| **Route** | `GET /kpi/pending`      |
| **Akses** | Manager, HR, Power User |

#### Alur Approval KPI

```
  Karyawan Submit KPI
         ↓
    Manager Review
      ↙       ↘
Approved ✅   Rejected ❌ + Feedback
     ↓
KPI Final Score Tercatat
```

| Aksi            | Route                        |
| --------------- | ---------------------------- |
| **Submit**      | `POST /kpi/submit/{id}`      |
| **Approve**     | `POST /kpi/approve/{id}`     |
| **Reject**      | `POST /kpi/reject/{id}`      |
| **Recalculate** | `POST /kpi/recalculate/{id}` |

---

## 5. Presences (Kehadiran)

| Item           | Detail                                 |
| -------------- | -------------------------------------- |
| **Route**      | Multiple routes (lihat tabel di bawah) |
| **Controller** | `PresencesController`                  |
| **Akses**      | Semua role                             |

### Fitur Kehadiran

| Menu           | Route                          | Deskripsi                                         |
| -------------- | ------------------------------ | ------------------------------------------------- |
| **Index**      | `GET /presences`               | Daftar kehadiran (DataTables)                     |
| **Check-in**   | `POST /presences`              | Catat kehadiran masuk dengan GPS + face detection |
| **Check-out**  | `GET/POST /presences/checkout` | Catat kehadiran pulang                            |
| **Calendar**   | `GET /presences/calendar`      | Tampilan kalender kehadiran bulanan               |
| **Statistics** | `GET /presences/statistics`    | Statistik & laporan kehadiran                     |
| **Export**     | `GET /presences/export`        | Export data kehadiran (HR/Power User only)        |

### Keamanan Presensi

| Fitur Keamanan              | Deskripsi                                        |
| --------------------------- | ------------------------------------------------ |
| **GPS Geofencing**          | Validasi lokasi check-in/out dalam radius kantor |
| **Face Detection**          | Deteksi wajah via kamera (face-api.js)           |
| **Liveness Detection**      | Pencegahan foto/video spoofing                   |
| **Rate Limiting**           | Maksimal 10 request per menit                    |
| **Suspicious Activity Log** | Logging aktivitas mencurigakan                   |
| **Distance Calculation**    | Haversine formula untuk validasi jarak GPS       |

### Check-in Flow

```
  Buka Halaman Check-in
         ↓
  Aktifkan Kamera + GPS
         ↓
  Capture Foto Wajah
         ↓
    Validasi Server
      ↙       ↘
GPS Valid +     GPS di luar radius ❌
Wajah Terdeteksi   atau
     ↓          Wajah tidak terdeteksi ❌
Check-in Berhasil ✅
```

> **⚠️ Penting:** Fitur check-in menggunakan rate limiting (throttle: 10 request/menit) untuk mencegah penyalahgunaan.

---

## 6. Inventory

### 6.1 Categories

| Item           | Detail                            |
| -------------- | --------------------------------- |
| **Route**      | `resource: /inventory-categories` |
| **Controller** | `InventoryCategoryController`     |
| **Akses**      | HR, Power User                    |

Mengelola **kategori inventaris** (misal: Printer, Proyektor, ATK, Alat Kerja).

---

### 6.2 Inventories

| Item           | Detail                                |
| -------------- | ------------------------------------- |
| **Route**      | `resource: /inventories`              |
| **Controller** | `InventoryController`                 |
| **Akses**      | Semua role (CRUD hanya HR/Power User) |

#### Data Inventaris

| Field                   | Deskripsi                                 |
| ----------------------- | ----------------------------------------- |
| **Name**                | Nama barang                               |
| **Category**            | Kategori barang                           |
| **Quantity**            | Jumlah stock                              |
| **Min Stock Threshold** | Batas minimum stock (alert jika di bawah) |
| **Location**            | Lokasi penyimpanan                        |
| **Purchase Date**       | Tanggal pembelian                         |
| **Status**              | `active`, `inactive`, `damaged`           |
| **Image**               | Foto barang (max 2MB)                     |

> **⚠️ Warning:** Barang dengan quantity di bawah `min_stock_threshold` akan ditandai dengan ikon peringatan merah (🔴 Low Stock).

---

### 6.3 Usage Logs

| Item           | Detail                            |
| -------------- | --------------------------------- |
| **Route**      | `resource: /inventory-usage-logs` |
| **Controller** | `InventoryUsageLogController`     |
| **Akses**      | Semua role (scoped per role)      |

Mencatat **log penggunaan** inventaris oleh karyawan:

- HR: Akses penuh
- Manager: Data departemen
- Employee: Data sendiri

---

### 6.4 Requests

| Item           | Detail                                     |
| -------------- | ------------------------------------------ |
| **Route**      | `resource: /inventory-requests`            |
| **Controller** | `InventoryRequestController`               |
| **Akses**      | Semua role (approve/reject: HR/Power User) |

#### Alur Permintaan Inventaris

```
  Karyawan Ajukan Request
         ↓
    Status: Pending
         ↓
      HR Review
      ↙       ↘
Approved ✅     Rejected ❌
+ Stock Berkurang
+ Usage Log Otomatis
```

**Saat Approve:**

- Status diupdate ke `approved`
- Quantity inventaris otomatis berkurang
- Usage log otomatis tercatat

---

## 7. Letters (Surat Menyurat)

### 7.1 Letters

| Item           | Detail                                                                  |
| -------------- | ----------------------------------------------------------------------- |
| **Route**      | `resource: /letters`                                                    |
| **Controller** | `LetterController`                                                      |
| **Akses**      | Semua user (create/submit), HR/Power User (approve/reject/print/export) |

#### Alur Pembuatan Surat

```
  Buat Surat Baru
         ↓
  Isi Dari Template
         ↓
  Submit Pengajuan
         ↓
      HR Review
      ↙       ↘
Approve ✅      Reject ❌
+ Nomor Surat    + Alasan Penolakan
  Digenerate
     ↓
  Print/Export PDF
     ↓
  Tanda Tangan Digital
     ↓
    Arsip
```

#### Fitur Surat

| Fitur       | Route                        | Deskripsi                            |
| ----------- | ---------------------------- | ------------------------------------ |
| **Submit**  | `POST /letters/{id}/submit`  | Ajukan surat untuk review            |
| **Approve** | `POST /letters/{id}/approve` | Setujui surat + generate nomor surat |
| **Reject**  | `POST /letters/{id}/reject`  | Tolak dengan alasan                  |
| **Print**   | `POST /letters/{id}/print`   | Cetak surat (PDF)                    |
| **Export**  | `GET /letters/{id}/export`   | Export surat sebagai PDF             |

#### Penomoran Otomatis

Nomor surat di-generate secara otomatis berdasarkan konfigurasi:

- Format: sesuai template di `LetterConfiguration`
- Counter otomatis increment

---

### 7.2 Templates

| Item           | Detail                        |
| -------------- | ----------------------------- |
| **Route**      | `resource: /letter-templates` |
| **Controller** | `LetterTemplateController`    |
| **Akses**      | HR, Power User                |

Mengelola **template surat** yang bisa digunakan ulang:

- Surat Keterangan Kerja
- Surat Peringatan
- Surat Tugas
- dll.

---

### 7.3 Configurations

| Item           | Detail                            |
| -------------- | --------------------------------- |
| **Route**      | `GET/POST /letter-configurations` |
| **Controller** | `LetterConfigurationController`   |
| **Akses**      | HR, Power User                    |

Konfigurasi **kop surat** dan pengaturan surat:

- Header dan footer
- Format penomoran surat
- Logo dan identitas perusahaan

---

### 7.4 Archives

| Item           | Detail                                          |
| -------------- | ----------------------------------------------- |
| **Route**      | `resource: /letter-archives` (index, show only) |
| **Controller** | `LetterArchiveController`                       |
| **Akses**      | Developer, HR, Power User                       |

Arsip surat-surat yang telah disetujui dan dicetak.

---

### 7.5 Signature Logs

| Item           | Detail                     |
| -------------- | -------------------------- |
| **Route**      | `GET /signature-logs`      |
| **Controller** | `SignatureController@logs` |
| **Akses**      | Semua user                 |

Log semua aktivitas tanda tangan digital (audit trail).

---

## 8. Digital Signature

| Item           | Detail                             |
| -------------- | ---------------------------------- |
| **Controller** | `SignatureController`              |
| **Akses**      | Semua user (verify: HR/Power User) |

### Fitur Tanda Tangan Digital

| Fitur             | Route                              | Deskripsi                              |
| ----------------- | ---------------------------------- | -------------------------------------- |
| **Signature Pad** | `GET /signatures/{type}/{id}/pad`  | Halaman tanda tangan (canvas)          |
| **Store**         | `POST /signatures/{type}/{id}`     | Simpan tanda tangan                    |
| **List**          | `GET /signatures/{type}/{id}/list` | Daftar tanda tangan dokumen            |
| **Verify**        | `POST /signatures/{id}/verify`     | Verifikasi keaslian (HR only)          |
| **Download**      | `GET /signatures/{id}/download`    | Download dokumen bertanda tangan (PDF) |
| **Validate**      | `GET /signatures/{id}/validate`    | Validasi autentikasi tanda tangan      |
| **Logs**          | `GET /signature-logs`              | Audit trail seluruh aktivitas          |

### Keamanan Signature

| Fitur               | Deskripsi                                                          |
| ------------------- | ------------------------------------------------------------------ |
| **Hash Validation** | Setiap tanda tangan memiliki hash unik untuk validasi              |
| **QR Code**         | Dokumen bertanda tangan memiliki QR code untuk public verification |
| **Audit Trail**     | Semua proses sign/verify tercatat di `SignatureVerification`       |
| **UUID Token**      | Setiap tanda tangan memiliki unique token untuk tracking           |

---

## 9. Reports & Reporting

### 9.1 Executive Report

| Item           | Detail                                   |
| -------------- | ---------------------------------------- |
| **Route**      | `GET /reports/executive`                 |
| **Controller** | `ReportingController@executiveDashboard` |
| **Akses**      | HR, Power User                           |

Dashboard eksekutif dengan ringkasan performa organisasi secara menyeluruh:

- Overview statistik SDM
- KPI performance summary
- Trend analysis
- Filter advanced berdasarkan periode, departemen, dll.

---

### 9.2 Monthly Report

| Item           | Detail                                                     |
| -------------- | ---------------------------------------------------------- |
| **Route**      | `GET /reports/monthly-recap`                               |
| **Controller** | `ReportingController@monthlyRecap`                         |
| **Akses**      | Manager, HR, Power User, Developer, Common Employee, Sales |

Rekap bulanan performa karyawan:

- Data kehadiran
- Skor KPI
- Incident/Awards summary

### Export Options

| Format  | Route                          | Akses                   |
| ------- | ------------------------------ | ----------------------- |
| **PDF** | `GET /reports/{id}/export-pdf` | Semua user              |
| **CSV** | `GET /reports/export-csv`      | Manager, HR, Power User |

---

## 10. Personal

### 10.1 My Profile

| Item           | Detail                      |
| -------------- | --------------------------- |
| **Route**      | `GET /my-profile`           |
| **Controller** | `MyProfileController@index` |
| **Akses**      | Semua user                  |

Halaman **profil pribadi** karyawan (read-only):

- Biodata lengkap
- Informasi pekerjaan (departemen, role, manager)
- Pendidikan terakhir
- Tempat & tanggal lahir

#### Ubah Password

| Route            | Deskripsi              |
| ---------------- | ---------------------- |
| `GET /profile`   | Halaman edit password  |
| `PATCH /profile` | Proses update password |

> **📝 Note:** Karyawan hanya dapat mengubah password. Perubahan data lain harus melalui Update Approvals oleh HR.

---

### 10.2 Knowledge Base

| Item           | Detail                          |
| -------------- | ------------------------------- |
| **Route**      | `GET /knowledge-base`           |
| **Controller** | `KnowledgeBaseController@index` |
| **Akses**      | Semua user                      |

Pusat informasi dan pengetahuan internal perusahaan — menggantikan modul chatbot/asisten virtual.

---

## 11. Role & Hak Akses

### Matriks Akses Per Modul

| Modul            | Power User | HR  |  Manager  | Developer | Employee  |   Sales   |
| ---------------- | :--------: | :-: | :-------: | :-------: | :-------: | :-------: |
| Dashboard        |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Employees        |     ✅     | ✅  | ✅ (dept) |    ✅     |    ❌     |    ❌     |
| Update Approvals |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Departments CRUD |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Org Chart        |     ✅     | ✅  |    ✅     |    ❌     |    ✅     |    ❌     |
| Roles            |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Tasks            |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Leave Requests   |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Leave Approve    |     ✅     | ✅  |    ✅     |    ❌     |    ❌     |    ❌     |
| Incidents        |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Payrolls CRUD    |     ✅     | ✅  |    ❌     |    ✅     |    ❌     |    ❌     |
| Payrolls View    |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| KPI Dashboard    |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Team/Dept KPI    |     ✅     | ✅  |    ✅     |    ❌     |    ❌     |    ❌     |
| KPI Approval     |     ✅     | ✅  |    ✅     |    ❌     |    ❌     |    ❌     |
| Inv Categories   |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Inventories CRUD |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Inv Usage Logs   |     ✅     | ✅  | ✅ (dept) |    ✅     | ✅ (self) | ✅ (self) |
| Inv Requests     |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Inv Approve      |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Letters Create   |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Letters Approve  |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Letter Templates |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Letter Config    |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Letter Archives  |     ✅     | ✅  |    ❌     |    ✅     |    ❌     |    ❌     |
| Signatures       |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Sig Verify       |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Exec Report      |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |
| Monthly Report   |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Export CSV       |     ✅     | ✅  |    ✅     |    ❌     |    ❌     |    ❌     |
| My Profile       |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Knowledge Base   |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Presences        |     ✅     | ✅  |    ✅     |    ✅     |    ✅     |    ✅     |
| Presences Export |     ✅     | ✅  |    ❌     |    ❌     |    ❌     |    ❌     |

---

## Arsitektur Teknis

### Tech Stack

| Layer              | Teknologi                           |
| ------------------ | ----------------------------------- |
| **Backend**        | Laravel (PHP)                       |
| **Frontend**       | Blade Templates + Mazer Admin Theme |
| **Database**       | MySQL (`hrappsprod`)                |
| **Auth**           | Laravel Breeze (Session-based)      |
| **DataTables**     | Yajra DataTables                    |
| **PDF**            | Barryvdh DomPDF                     |
| **Charts**         | Chart.js / Google Charts            |
| **Face Detection** | face-api.js                         |
| **Rich Text**      | TinyMCE                             |
| **Date Picker**    | Flatpickr                           |
| **Alerts**         | SweetAlert2                         |

### Navigasi Sidebar

```
├── 📊 Dashboard
├── 👥 HR Management
│   ├── Employees
│   ├── Update Approvals
│   ├── Departments
│   ├── Roles
│   ├── Tasks
│   ├── Leave Requests
│   └── Incidents & Awards
├── 💰 Payroll & KPI
│   ├── Payrolls
│   ├── KPI Dashboard
│   ├── Team KPI
│   ├── Department KPI
│   └── Pending Approvals
├── 📦 Inventory
│   ├── Categories
│   ├── Inventories
│   ├── Usage Logs
│   └── Requests
├── ✉️ Letters
│   ├── Letters
│   ├── Templates
│   ├── Configurations
│   ├── Archives
│   └── Signature Logs
├── 📊 Reports (Admin/Manager)
│   ├── Executive Report
│   └── Monthly Report
└── 👤 Personal
    ├── My Profile
    ├── Presences
    ├── Knowledge Base
    └── Logout
```

---

> **Document Version:** 1.0
> **Generated:** 7 Maret 2026
> **Maintained by:** PT Aratech Nusantara Indonesia
