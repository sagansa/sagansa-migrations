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
        if (!Schema::hasTable('e_products')) {
            Schema::create('e_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('online_category_id')->index('e_products_online_category_id_foreign');
            $table->unsignedBigInteger('store_id')->index('e_products_store_id_foreign');
            $table->unsignedBigInteger('product_id')->index('e_products_product_id_foreign');
            $table->string('image')->nullable();
            $table->bigInteger('quantity_stock');
            $table->bigInteger('price');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->foreign(['online_category_id'])->references(['id'])->on('online_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('e_products', function (Blueprint $table) {
            $table->dropForeign('e_products_online_category_id_foreign');
            $table->dropForeign('e_products_product_id_foreign');
            $table->dropForeign('e_products_store_id_foreign');
        });
        Schema::dropIfExists('e_products');
    }
};
