<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom `stock` (int, default 0) ke tabel products di koneksi mysql
 * (utama). Dipakai oleh modul Production untuk ledger stok hasil produksi:
 *  • direction=in  → products.stock -= quantity (bahan baku dikonsumsi)
 *  • direction=out → products.stock += quantity (produk hasil produksi)
 *
 * Catatan: kolom `stock` sudah ada di koneksi mysql_ops (online-shop), tapi
 * koneksi mysql (yang dipakai apps/admin & services/api untuk operasional)
 * belum memilikinya. Production butuh satu sumber kebenaran stok utama.
 */
return new class extends Migration {
    protected $connection = 'mysql';

    public function up(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'stock')) {
            Schema::table('products', function (Blueprint $table) {
                // Pakai bigInteger supaya bisa menampung koma (decimal stock),
                // walau kolomnya integer. Stok hasil produksi utk produk jadi
                // biasanya bilangan bulat (cup, porsi, dll). Negatif diizinkan
                // sesuai keputusan desain (warning only — izinkan minus).
                $table->bigInteger('stock')->default(0)->after('remaining');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('stock');
            });
        }
    }
};
