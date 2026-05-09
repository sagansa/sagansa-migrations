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
        if (!Schema::hasTable('payment_receipt_presence')) {
            Schema::create('payment_receipt_presence', function (Blueprint $table) {
            $table->unsignedBigInteger('presence_id')->index('payment_receipt_presence_presence_id_foreign');
            $table->unsignedBigInteger('payment_receipt_id')->index('payment_receipt_presence_payment_receipt_id_foreign');
            $table->foreign(['payment_receipt_id'])->references(['id'])->on('payment_receipts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['presence_id'])->references(['id'])->on('presences')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_receipt_presence', function (Blueprint $table) {
            $table->dropForeign('payment_receipt_presence_payment_receipt_id_foreign');
            $table->dropForeign('payment_receipt_presence_presence_id_foreign');
        });
        Schema::dropIfExists('payment_receipt_presence');
    }
};
