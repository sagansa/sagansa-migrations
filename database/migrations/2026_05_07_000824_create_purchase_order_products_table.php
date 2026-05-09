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
        if (!Schema::hasTable('purchase_order_products')) {
            Schema::create('purchase_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('purchase_order_products_product_id_foreign');
            $table->unsignedBigInteger('purchase_order_id')->index('purchase_order_products_purchase_order_id_foreign');
            $table->bigInteger('quantity_product');
            $table->unsignedBigInteger('unit_id')->index('purchase_order_products_unit_id_foreign');
            $table->bigInteger('quantity_invoice');
            $table->bigInteger('subtotal_invoice');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['purchase_order_id'])->references(['id'])->on('purchase_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_products', function (Blueprint $table) {
            $table->dropForeign('purchase_order_products_product_id_foreign');
            $table->dropForeign('purchase_order_products_purchase_order_id_foreign');
            $table->dropForeign('purchase_order_products_unit_id_foreign');
        });
        Schema::dropIfExists('purchase_order_products');
    }
};
