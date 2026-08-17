<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah soft-deletes (`deleted_at`) pada tabel `users` di koneksi
     * mysql_auth (sagansa_user) untuk fitur hapus akun aplikasi EV Charge ID
     * (syarat App Store 5.1.1(v)): baris user di-anonimkan lalu di-soft-delete
     * sehingga data relasional (charges, vehicles, dsb.) tetap utuh.
     *
     * Idempoten — kolom sudah ada di sebagian environment, jadi guard
     * hasColumn mencegah duplikasi saat dijalankan lintas service yang
     * memakai DB ini. Mirror di ev/backend:
     * 2026_08_17_000020_add_soft_deletes_to_users_table.php.
     */
    public function up(): void
    {
        if (Schema::connection('mysql_auth')->hasColumn('users', 'deleted_at')) {
            return;
        }

        Schema::connection('mysql_auth')->table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        if (! Schema::connection('mysql_auth')->hasColumn('users', 'deleted_at')) {
            return;
        }

        Schema::connection('mysql_auth')->table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
