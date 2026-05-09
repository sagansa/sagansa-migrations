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
        if (!Schema::hasTable('detail_sales_orders')) {
            Schema::create('detail_sales_orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('product_id')->index('detail_sales_orders_product_id_foreign');
            $table->integer('quantity');
            $table->bigInteger('unit_price');
            $table->bigInteger('subtotal_price');
            $table->unsignedBigInteger('sales_order_id')->index('detail_sales_orders_sales_order_id_foreign');
            $table->timestamps();
            $table->foreign(['sales_order_id'], 'detail_sales_orders_ibfk_1')->references(['id'])->on('sales_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['product_id'], 'detail_sales_orders_ibfk_2')->references(['id'])->on('products')->onUpdate('no action')->onDelete('no action');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_sales_orders', function (Blueprint $table) {
            $table->dropForeign('detail_sales_orders_ibfk_1');
            $table->dropForeign('detail_sales_orders_ibfk_2');
        });
        Schema::dropIfExists('detail_sales_orders');
    }
};
