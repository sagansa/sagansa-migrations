<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom `is_active` ke tabel `stores` pada DB utama (mysql).
 *
 * Latar belakang: kolom ini hanya didefinisikan di migration lokal
 * services/api/database/migrations/2024_07_31_000000_create_stores_table.php,
 * tetapi TIDAK ada di schema produksi (services/migration) sehingga
 * Store::active()->count() di StorageStockController::todayStatus mengakibatkan
 * error "Unknown column 'is_active'" → 500 pada endpoint
 * GET /storage-stocks/today-status.
 *
 * Default true agar store eksisting dianggap aktif (menyamakan perilaku dgn
 * migration lokal services/api). Idempoten via Schema::hasColumn.
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        if (!Schema::hasTable('stores')) {
            return;
        }

        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('email');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('stores')) {
            return;
        }

        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
