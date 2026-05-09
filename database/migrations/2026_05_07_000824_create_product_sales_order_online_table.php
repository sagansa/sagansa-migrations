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
        if (!Schema::hasTable('product_sales_order_online')) {
            Schema::create('product_sales_order_online', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->index('product_sales_order_online_product_id_foreign');
            $table->unsignedBigInteger('sales_order_online_id')->index('product_sales_order_online_sales_order_online_id_foreign');
            $table->integer('quantity');
            $table->bigInteger('price');
            $table->timestamps();
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['sales_order_online_id'])->references(['id'])->on('sales_order_onlines')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sales_order_online', function (Blueprint $table) {
            $table->dropForeign('product_sales_order_online_product_id_foreign');
            $table->dropForeign('product_sales_order_online_sales_order_online_id_foreign');
        });
        Schema::dropIfExists('product_sales_order_online');
    }
};
