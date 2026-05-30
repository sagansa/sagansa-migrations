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
        if (!Schema::hasTable('invoice_purchase_payment_receipt')) {
            Schema::create('invoice_purchase_payment_receipt', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_purchase_id')->index('invoice_purchase_payment_receipt_invoice_purchase_id_foreign');
            $table->unsignedBigInteger('payment_receipt_id')->index('invoice_purchase_payment_receipt_payment_receipt_id_foreign');
            $table->foreign(['invoice_purchase_id'])->references(['id'])->on('invoice_purchases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['payment_receipt_id'])->references(['id'])->on('payment_receipts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_purchase_payment_receipt', function (Blueprint $table) {
            $table->dropForeign('invoice_purchase_payment_receipt_invoice_purchase_id_foreign');
            $table->dropForeign('invoice_purchase_payment_receipt_payment_receipt_id_foreign');
        });
        Schema::dropIfExists('invoice_purchase_payment_receipt');
    }
};
