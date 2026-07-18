<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Daftar ingredient (bahan baku) dari sebuah resep.
 * Relasi N:1 ke recipes. Setiap baris = 1 product ingredient + qty yang
 * dibutuhkan per sekali jalan resep (lihat recipes.output_qty).
 */
return new class extends Migration {
    protected $connection = 'mysql';

    public function up(): void
    {
        if (!Schema::hasTable('recipe_ingredients')) {
            Schema::create('recipe_ingredients', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('recipe_id')->index('recipe_ingredients_recipe_id_foreign');
                $table->unsignedBigInteger('product_id')->index('recipe_ingredients_product_id_foreign');
                // Qty dibutuhkan per output_qty resep (mis. butuh 20g kopi utk
                // hasil 1 cup). Pakai decimal(12,3) supaya support berat/volume.
                $table->decimal('quantity', 12, 3);
                $table->unsignedBigInteger('unit_id')->nullable()->index('recipe_ingredients_unit_id_foreign');
                // Ingredient wajib vs opsional (boleh skip saat produksi).
                $table->boolean('is_optional')->default(false);
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign(['recipe_id'])->references(['id'])->on('recipes')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['product_id'])->references(['id'])->on('products')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['unit_id'])->references(['id'])->on('units')
                    ->onUpdate('cascade')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
