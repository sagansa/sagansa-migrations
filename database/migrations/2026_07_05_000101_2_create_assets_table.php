<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Tabel instance aset yang terlacak. Dibuat secara manual via UI manajemen
 * aset ATAU otomatis saat invoice pembelian dibuat untuk produk ber-flag
 * `is_asset` (lihat kolom `source_detail_invoice_id`).
 *
 * Catatan FK ke `users` (created_by_id, pic_user_id) bersifat LONGGAR
 * (loose) tanpa foreign() karena `users` berada di koneksi DB berbeda
 * (`mysql_auth`), mengikuti konvensi tabel bisnis lain seperti
 * `storage_stocks`, `presences`, `employee_locations`.
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (!Schema::hasTable('assets')) {
            Schema::create('assets', function (Blueprint $table) {
                $table->bigIncrements('id');
                // Kode unik aset, mis. A-0012. Bisa diturunkan dari SKU produk
                // + sequence saat auto-link dari pembelian.
                $table->string('code')->unique();
                $table->string('name');

                // Relasi loose ke products (cross-service, tidak enforce FK).
                $table->unsignedBigInteger('product_id')->nullable()->index('assets_product_id_index');

                $table->unsignedBigInteger('asset_category_id')->index('assets_asset_category_id_foreign');
                $table->unsignedBigInteger('store_id')->index('assets_store_id_foreign');

                // PIC (penanggung jawab) spesifik per aset. Bisa null karena
                // default pengingat dikirim per-role (storage-staff/manager/admin)
                // di store terkait.
                $table->unsignedBigInteger('pic_user_id')->nullable()->index('assets_pic_user_id_index');

                // 1=baik, 2=rusak_ringan, 3=rusak_berat, 4=hilang.
                $table->tinyInteger('condition')->default(1);
                // 1=aktif, 2=dipelihara, 3=non_aktif.
                $table->tinyInteger('status')->default(1);

                $table->string('photo')->nullable();
                $table->date('purchase_date')->nullable();

                // Inti penjadwalan: kapan harus diperiksa berikutnya & terakhir.
                $table->timestamp('next_check_at')->nullable()->index('assets_next_check_at_index');
                $table->timestamp('last_check_at')->nullable();

                // Link ke baris detail_invoice saat aset tercipta dari pembelian.
                $table->unsignedBigInteger('source_detail_invoice_id')->nullable()->index('assets_source_detail_invoice_id_index');

                // Audit kepemilikan (loose ke users cross-DB).
                $table->unsignedBigInteger('created_by_id')->nullable()->index('assets_created_by_id_index');

                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['store_id', 'status'], 'assets_store_status_index');

                $table->foreign('asset_category_id', 'assets_asset_category_id_foreign')
                    ->references('id')->on('asset_categories')
                    ->onUpdate('cascade')->onDelete('restrict');
                $table->foreign('store_id', 'assets_store_id_foreign')
                    ->references('id')->on('stores')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (Schema::hasTable('assets')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->dropForeign('assets_asset_category_id_foreign');
                $table->dropForeign('assets_store_id_foreign');
            });
        }
        Schema::dropIfExists('assets');
    }
};
