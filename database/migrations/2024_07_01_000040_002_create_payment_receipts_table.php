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
        if (!Schema::hasTable('payment_receipts')) {
            Schema::create('payment_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->tinyInteger('payment_for');
            $table->string('image_adjust')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->bigInteger('total_amount')->nullable();
            $table->bigInteger('transfer_amount')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable()->index('supplier_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->foreign(['supplier_id'], 'payment_receipts_ibfk_1')->references(['id'])->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'payment_receipts_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_receipts', function (Blueprint $table) {
            $table->dropForeign('payment_receipts_ibfk_1');
            $table->dropForeign('payment_receipts_ibfk_2');
        });
        Schema::dropIfExists('payment_receipts');
    }
};
