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
        if (!Schema::hasTable('closing_store_purchase_order')) {
            Schema::create('closing_store_purchase_order', function (Blueprint $table) {
            $table->unsignedBigInteger('closing_store_id')->index('closing_store_purchase_order_closing_store_id_foreign');
            $table->unsignedBigInteger('purchase_order_id')->index('closing_store_purchase_order_purchase_order_id_foreign');
            $table->foreign(['closing_store_id'])->references(['id'])->on('closing_stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['purchase_order_id'])->references(['id'])->on('purchase_orders')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('closing_store_purchase_order', function (Blueprint $table) {
            $table->dropForeign('closing_store_purchase_order_closing_store_id_foreign');
            $table->dropForeign('closing_store_purchase_order_purchase_order_id_foreign');
        });
        Schema::dropIfExists('closing_store_purchase_order');
    }
};
