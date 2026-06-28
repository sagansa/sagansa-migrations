<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Subscription & Billing — Tabel: payments
 *
 * Record pembayaran masuk (dari webhook Xendit/Midtrans). Banyak per billing_cycle
 * (mis. retry, partial payment).
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('payments')) {
            Schema::connection($this->connection)->create('payments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('billing_cycle_id');
                $table->unsignedInteger('amount');
                $table->string('method', 30)->comment('VA, QRIS, EWALLET, CREDIT_CARD, dll');
                $table->string('provider', 20)->comment('xendit | midtrans');
                $table->string('provider_payment_id', 100)->nullable()->comment('ID dari provider');
                $table->timestamp('paid_at')->nullable();
                $table->json('metadata')->nullable()->comment('Raw payload webhook');
                $table->timestamps();

                try {
                    $table->foreign('billing_cycle_id')->references('id')->on('billing_cycles')->cascadeOnDelete();
                } catch (\Throwable $e) {
                }
                $table->index('billing_cycle_id');
                $table->index('provider_payment_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('payments');
    }
};
