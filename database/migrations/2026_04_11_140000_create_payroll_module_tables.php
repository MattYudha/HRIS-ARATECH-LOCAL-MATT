<?php
use Illuminate\Database\Migrations\Migration;

// Migration ditarik kembali karena salah konteks (sebelumnya dianggap modul HR Payroll)
// File ini dikosongkan agar tidak mengganggu proses php artisan migrate.
return new class extends Migration {
    public function up(): void {}
    public function down(): void {}
};
