<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Master resep produksi: 1 produk output → banyak ingredient default.
 *
 * Sebuah produk output bisa punya beberapa versi resep (mis. formula lama vs
 * baru), tapi hanya satu yang `is_active=true` per produk pada satu waktu.
 *
 * Dipakai production sebagai starting point: user pilih output product → resep
 * aktifnya auto-load sebagai daftar ingredient default → boleh override qty
 * atau tambah/kurang ingredient per produksi (snapshot di production_items).
 */
return new class extends Migration {
    protected $connection = 'mysql';

    public function up(): void
    {
        if (!Schema::hasTable('recipes')) {
            Schema::create('recipes', function (Blueprint $table) {
                $table->bigIncrements('id');
                // Produk hasil (output) yang dihasilkan resep ini.
                $table->unsignedBigInteger('product_id')->index('recipes_product_id_foreign');
                // Jumlah output yang dihasilkan sekali pakai resep ini (mis.
                // "resep ini menghasilkan 10 cup"). Dipakai utk skala proporsi
                // ingredient saat user input qty output berbeda.
                $table->decimal('output_qty', 12, 3)->default(1);
                $table->unsignedBigInteger('output_unit_id')->nullable()->index('recipes_output_unit_id_foreign');
                $table->string('name')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by_id')->nullable()->index('recipes_created_by_id_foreign');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign(['product_id'])->references(['id'])->on('products')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['output_unit_id'])->references(['id'])->on('units')
                    ->onUpdate('cascade')->onDelete('set null');
                // created_by_id: tidak pakai FK karena user di DB berbeda
                // (sagansa_user), konsisten dgn kolom *_by_id lain di project.
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
