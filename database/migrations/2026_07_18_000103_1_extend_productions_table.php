<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Perubahan pada tabel productions:
 * 1. Tambah `recipe_id` (nullable) — link ke resep master yang dipakai sbg
 *    starting point produksi ini. Nullable karena produksi boleh tanpa resep
 *    (fully manual).
 * 2. Tambah `applied_at` (nullable timestamp) — penanda idempotensi ledger
 *    stok. Saat production berubah status → "complete", sistem apply mutasi
 *    stok (kurangi ingredient, tambah output) lalu set applied_at = now().
 *    Re-trigger tidak akan apply dobel karena dicek `WHERE applied_at IS NULL`.
 * 3. Drop pivot legacy `product_production` yang redundan dgn production_tos
 *    (sekarang diwakili production_items direction=out).
 */
return new class extends Migration {
    protected $connection = 'mysql';

    public function up(): void
    {
        if (Schema::hasTable('productions')) {
            Schema::table('productions', function (Blueprint $table) {
                if (!Schema::hasColumn('productions', 'recipe_id')) {
                    $table->unsignedBigInteger('recipe_id')->nullable()
                        ->after('status')->index('productions_recipe_id_foreign');
                    $table->foreign(['recipe_id'], 'productions_recipe_id_foreign')
                        ->references(['id'])->on('recipes')
                        ->onUpdate('cascade')->onDelete('set null');
                }
                if (!Schema::hasColumn('productions', 'applied_at')) {
                    $table->timestamp('applied_at')->nullable()->after('recipe_id');
                }
            });
        }

        // Drop pivot redundan. Hasil produksi sudah dicatat di production_items
        // direction=out (atau production_tos bila masih dipakai Filament lama).
        if (Schema::hasTable('product_production')) {
            // Drop FK dulu bila ada, lalu tabel. Nama FK default Laravel:
            try { Schema::table('product_production', function (Blueprint $table) {
                $table->dropForeign('product_production_product_id_foreign');
                $table->dropForeign('product_production_production_id_foreign');
            }); } catch (\Throwable $e) {}
            Schema::dropIfExists('product_production');
        }
    }

    public function down(): void
    {
        // Restore pivot legacy (tanpa data — sudah dihapus). Sengaja minimal
        // karena down() jarang dipakai di project ini.
        if (!Schema::hasTable('product_production')) {
            Schema::create('product_production', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->index('product_production_product_id_foreign');
                $table->unsignedBigInteger('production_id')->index('product_production_production_id_foreign');
                $table->decimal('quantity');
                $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['production_id'])->references(['id'])->on('productions')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('productions')) {
            Schema::table('productions', function (Blueprint $table) {
                if (Schema::hasColumn('productions', 'applied_at')) {
                    $table->dropColumn('applied_at');
                }
                if (Schema::hasColumn('productions', 'recipe_id')) {
                    try { $table->dropForeign('productions_recipe_id_foreign'); } catch (\Throwable $e) {}
                    $table->dropColumn('recipe_id');
                }
            });
        }
    }
};
