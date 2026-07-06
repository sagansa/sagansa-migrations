<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Tabel header hasil pemeriksaan aset (1 baris = 1 sesi check untuk 1 aset).
 * Baris ini dibuat user saat mengeksekusi form check berkala. Setiap check
 * bisa punya banyak `asset_check_items` (checklist per kategori).
 *
 * Saat check dibuat:
 *   - asset.last_check_at di-update = check_date.
 *   - asset.next_check_at di-update = check_date + asset.assetCategory.frequency_days.
 *   - Jika severity >= ringan, otomatis dibuatkan baris di `asset_issues`.
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (!Schema::hasTable('asset_checks')) {
            Schema::create('asset_checks', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('asset_id')->index('asset_checks_asset_id_foreign');

                // Checker (loose ke users cross-DB).
                $table->unsignedBigInteger('checked_by_id')->nullable()->index('asset_checks_checked_by_id_index');

                $table->date('check_date');
                // Kondisi sebelum & sesudah check (1=baik..4=hilang).
                $table->tinyInteger('condition_before');
                $table->tinyInteger('condition_after');
                // 1=ok, 2=ringan, 3=sedang, 4=berat.
                $table->tinyInteger('severity')->default(1);
                // 1=submitted, 2=approved.
                $table->tinyInteger('status')->default(1);

                $table->text('notes')->nullable();
                // Array path foto (JSON) — relatif terhadap disk `public`.
                $table->json('photos')->nullable();

                // Geotag wajib saat check dilapangan.
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();

                $table->timestamps();

                $table->index(['asset_id', 'check_date'], 'asset_checks_asset_check_date_index');
                $table->index('check_date', 'asset_checks_check_date_index');

                $table->foreign('asset_id', 'asset_checks_asset_id_foreign')
                    ->references('id')->on('assets')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (Schema::hasTable('asset_checks')) {
            Schema::table('asset_checks', function (Blueprint $table) {
                $table->dropForeign('asset_checks_asset_id_foreign');
            });
        }
        Schema::dropIfExists('asset_checks');
    }
};
