<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Snapshot item produksi: menyatukan production_main_froms (bahan baku dari
 * purchase invoice), production_support_froms (ingredient tambahan manual),
 * dan production_tos (output hasil produksi) jadi satu tabel dengan kolom
 * discriminator `direction` (in/out) dan `source` (recipe_default|invoice|manual).
 *
 * Snapshot (bukan FK langsung ke recipe_ingredients) supaya history produksi
 * tetap akurat walau resep master berubah di kemudian hari.
 */
return new class extends Migration {
    protected $connection = 'mysql';

    public function up(): void
    {
        if (!Schema::hasTable('production_items')) {
            Schema::create('production_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('production_id')->index('production_items_production_id_foreign');
                $table->unsignedBigInteger('product_id')->index('production_items_product_id_foreign');

                // in  = ingredient (bahan baku yang dikonsumsi, stok berkurang)
                // out = output (produk hasil produksi, stok bertambah)
                $table->enum('direction', ['in', 'out']);

                // recipe_default = di-load dari resep master
                // invoice        = bahan baku dari purchase invoice tertentu
                // manual         = input bebas oleh user
                $table->enum('source', ['recipe_default', 'invoice', 'manual'])->default('manual');

                $table->decimal('quantity', 12, 3);
                $table->unsignedBigInteger('unit_id')->nullable()->index('production_items_unit_id_foreign');

                // Hanya relevan bila source=invoice: baris detail_invoice
                // (bahan baku dari pembelian) yang dipakai sebagai ingredient.
                $table->unsignedBigInteger('detail_invoice_id')->nullable()->index('production_items_detail_invoice_id_foreign');

                // Hanya relevan bila source=recipe_default: link ke baris resep
                // (untuk audit/jejak). Walau qty di sini bisa di-override user.
                $table->unsignedBigInteger('recipe_ingredient_id')->nullable()->index('production_items_recipe_ingredient_id_foreign');

                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign(['production_id'])->references(['id'])->on('productions')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['product_id'])->references(['id'])->on('products')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['unit_id'])->references(['id'])->on('units')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign(['detail_invoice_id'])->references(['id'])->on('detail_invoices')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign(['recipe_ingredient_id'])->references(['id'])->on('recipe_ingredients')
                    ->onUpdate('cascade')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('production_items');
    }
};
