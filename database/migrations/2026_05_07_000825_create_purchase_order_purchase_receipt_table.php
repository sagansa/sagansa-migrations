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
        if (!Schema::hasTable('purchase_order_purchase_receipt')) {
            Schema::create('purchase_order_purchase_receipt', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_order_id')->index('purchase_order_purchase_receipt_purchase_order_id_foreign');
            $table->unsignedBigInteger('purchase_receipt_id')->index('purchase_order_purchase_receipt_purchase_receipt_id_foreign');
            $table->foreign(['purchase_order_id'])->references(['id'])->on('purchase_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['purchase_receipt_id'])->references(['id'])->on('purchase_receipts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_purchase_receipt', function (Blueprint $table) {
            $table->dropForeign('purchase_order_purchase_receipt_purchase_order_id_foreign');
            $table->dropForeign('purchase_order_purchase_receipt_purchase_receipt_id_foreign');
        });
        Schema::dropIfExists('purchase_order_purchase_receipt');
    }
};
