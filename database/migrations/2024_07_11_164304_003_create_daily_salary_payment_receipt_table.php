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
        if (!Schema::hasTable('daily_salary_payment_receipt')) {
            Schema::create('daily_salary_payment_receipt', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_receipt_id')->index('daily_salary_payment_receipt_payment_receipt_id_foreign');
            $table->unsignedBigInteger('daily_salary_id')->index('daily_salary_payment_receipt_daily_salary_id_foreign');
            $table->foreign(['daily_salary_id'])->references(['id'])->on('daily_salaries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['payment_receipt_id'])->references(['id'])->on('payment_receipts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_salary_payment_receipt', function (Blueprint $table) {
            $table->dropForeign('daily_salary_payment_receipt_daily_salary_id_foreign');
            $table->dropForeign('daily_salary_payment_receipt_payment_receipt_id_foreign');
        });
        Schema::dropIfExists('daily_salary_payment_receipt');
    }
};
