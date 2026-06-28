<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Subscription & Billing — Tabel: billing_cycles
 *
 * Invoice/tagihan per tenant per bulan. Saat di-generate (tgl 5, cron), pricing
 * di-snapshot ke snapshot_plan (immutable) agar invoice lampau tidak ikut berubah
 * saat admin ubah harga kemudian.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('billing_cycles')) {
            Schema::connection($this->connection)->create('billing_cycles', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('tenant_id');
                $table->uuid('subscription_id');
                $table->uuid('plan_id');
                // Periode pemakaian yang ditagih (bulan N-1)
                $table->unsignedSmallInteger('period_year');
                $table->unsignedTinyInteger('period_month');
                // Nominal charge
                $table->unsignedInteger('pos_charge')->default(0);
                $table->unsignedInteger('attendance_charge')->default(0);
                $table->unsignedInteger('discount_amount')->default(0);
                $table->unsignedInteger('total_charge')->default(0);
                // Breakdown
                $table->json('pos_breakdown')->nullable()->comment('[{store_id, store_name, revenue, charge}]');
                $table->unsignedInteger('attendance_employees_count')->default(0);
                $table->json('snapshot_plan')->nullable()->comment('Snapshot harga & diskon saat invoice dibuat (immutable)');
                // Status & tanggal
                $table->enum('status', ['draft', 'issued', 'paid', 'overdue', 'cancelled'])->default('draft');
                $table->date('issued_at')->nullable()->comment('Tanggal invoice dibuat (tgl 5)');
                $table->date('due_at')->nullable()->comment('Jatuh tempo (tgl 10)');
                $table->timestamp('paid_at')->nullable();
                // Provider integration
                $table->string('payment_provider', 20)->nullable()->comment('xendit | midtrans');
                $table->string('provider_invoice_id', 100)->nullable();
                $table->text('provider_invoice_url')->nullable();
                $table->timestamps();

                try {
                    $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                    $table->foreign('subscription_id')->references('id')->on('subscriptions')->cascadeOnDelete();
                    $table->foreign('plan_id')->references('id')->on('plans')->restrictOnDelete();
                } catch (\Throwable $e) {
                }
                // 1 invoice per tenant per periode
                $table->unique(['tenant_id', 'period_year', 'period_month']);
                $table->index(['tenant_id', 'status']);
                $table->index('due_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('billing_cycles');
    }
};
