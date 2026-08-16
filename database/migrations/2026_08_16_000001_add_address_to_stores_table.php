<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom `address` ke tabel `stores` pada DB utama (mysql).
 *
 * Kolom ini sudah dipakai services/api (StoreFactory & skema lokal
 * services/api/database/migrations/2024_07_31_000000_create_stores_table.php)
 * tetapi belum ada di schema kanonik services/migration, sehingga DB hasil
 * migrasi penuh (mis. sagansa_test) kehilangan kolom tersebut. Idempoten
 * via Schema::hasColumn.
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
            if (!Schema::hasColumn('stores', 'address')) {
                $table->text('address')->nullable()->after('nickname');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('stores') || !Schema::hasColumn('stores', 'address')) {
            return;
        }

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
