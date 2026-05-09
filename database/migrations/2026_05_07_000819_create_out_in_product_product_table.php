<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('out_in_product_product')) {
            Schema::create('out_in_product_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->index('out_in_product_product_product_id_foreign');
            $table->unsignedBigInteger('out_in_product_id')->index('out_in_product_product_out_in_product_id_foreign');
            $table->decimal('quantity');
            $table->foreign(['out_in_product_id'])->references(['id'])->on('out_in_products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('out_in_product_product', function (Blueprint $table) {
            $table->dropForeign('out_in_product_product_out_in_product_id_foreign');
            $table->dropForeign('out_in_product_product_product_id_foreign');
        });
        Schema::dropIfExists('out_in_product_product');
    }
};
