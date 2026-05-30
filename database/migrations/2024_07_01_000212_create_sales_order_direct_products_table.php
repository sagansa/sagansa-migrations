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
        if (!Schema::hasTable('sales_order_direct_products')) {
            Schema::create('sales_order_direct_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('e_product_id')->index('sales_order_direct_products_e_product_id_foreign');
            $table->integer('quantity');
            $table->bigInteger('price');
            $table->unsignedBigInteger('sales_order_direct_id')->index('sales_order_direct_products_sales_order_direct_id_foreign');
            $table->bigInteger('amount');
            $table->timestamps();
            $table->foreign(['e_product_id'])->references(['id'])->on('e_products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['sales_order_direct_id'])->references(['id'])->on('sales_order_directs')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_direct_products', function (Blueprint $table) {
            $table->dropForeign('sales_order_direct_products_e_product_id_foreign');
            $table->dropForeign('sales_order_direct_products_sales_order_direct_id_foreign');
        });
        Schema::dropIfExists('sales_order_direct_products');
    }
};
