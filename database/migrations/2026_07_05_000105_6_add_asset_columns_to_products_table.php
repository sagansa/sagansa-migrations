<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Tandai produk yang termasuk aset. Saat invoice pembelian dibuat untuk
 * produk dengan `is_asset=true`, satu instance aset (row di `assets`) akan
 * otomatis tercipta per unit (qty) — lihat Asset model & ProcurementController.
 *
 * `asset_category_id` menentukan kategori (frekuensi + checklist) aset tsb.
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'is_asset')) {
                    $table->boolean('is_asset')->default(false)->after('request');
                }
                if (!Schema::hasColumn('products', 'asset_category_id')) {
                    $table->unsignedBigInteger('asset_category_id')->nullable()->after('is_asset');
                    $table->foreign('asset_category_id', 'products_asset_category_id_foreign')
                        ->references('id')->on('asset_categories')
                        ->onUpdate('cascade')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'asset_category_id')) {
                    $table->dropForeign('products_asset_category_id_foreign');
                    $table->dropColumn('asset_category_id');
                }
                if (Schema::hasColumn('products', 'is_asset')) {
                    $table->dropColumn('is_asset');
                }
            });
        }
    }
};
