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
        if (!Schema::hasTable('product_remaining_stock')) {
            Schema::create('product_remaining_stock', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->index('product_remaining_stock_product_id_foreign');
            $table->unsignedBigInteger('remaining_stock_id')->index('product_remaining_stock_remaining_stock_id_foreign');
            $table->integer('quantity')->nullable();
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['remaining_stock_id'])->references(['id'])->on('remaining_stocks')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_remaining_stock', function (Blueprint $table) {
            $table->dropForeign('product_remaining_stock_product_id_foreign');
            $table->dropForeign('product_remaining_stock_remaining_stock_id_foreign');
        });
        Schema::dropIfExists('product_remaining_stock');
    }
};
