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
        if (!Schema::hasTable('fuel_service_payment_receipt')) {
            Schema::create('fuel_service_payment_receipt', function (Blueprint $table) {
            $table->unsignedBigInteger('fuel_service_id')->index('fuel_service_payment_receipt_fuel_service_id_foreign');
            $table->unsignedBigInteger('payment_receipt_id')->index('fuel_service_payment_receipt_payment_receipt_id_foreign');
            $table->foreign(['fuel_service_id'])->references(['id'])->on('fuel_services')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['payment_receipt_id'])->references(['id'])->on('payment_receipts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_service_payment_receipt', function (Blueprint $table) {
            $table->dropForeign('fuel_service_payment_receipt_fuel_service_id_foreign');
            $table->dropForeign('fuel_service_payment_receipt_payment_receipt_id_foreign');
        });
        Schema::dropIfExists('fuel_service_payment_receipt');
    }
};
