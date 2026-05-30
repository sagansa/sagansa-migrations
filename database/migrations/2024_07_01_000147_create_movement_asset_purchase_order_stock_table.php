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
        if (!Schema::hasTable('movement_asset_purchase_order_stock')) {
            Schema::create('movement_asset_purchase_order_stock', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_order_stock_id');
            $table->unsignedBigInteger('movement_asset_id')->index('movement_asset_purchase_order_stock_movement_asset_id_foreign');
            $table->foreign(['movement_asset_id'])->references(['id'])->on('movement_assets')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movement_asset_purchase_order_stock', function (Blueprint $table) {
            $table->dropForeign('movement_asset_purchase_order_stock_movement_asset_id_foreign');
        });
        Schema::dropIfExists('movement_asset_purchase_order_stock');
    }
};
