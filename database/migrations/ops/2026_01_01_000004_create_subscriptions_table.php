<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Subscription & Billing — Tabel: subscriptions
 *
 * 1 subscription per tenant. Status: trialing (masa percobaan), active, suspended,
 * cancelled, exempt (tenant flag billing_exempt).
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('subscriptions')) {
            Schema::connection($this->connection)->create('subscriptions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('tenant_id');
                $table->uuid('plan_id');
                $table->enum('status', ['trialing', 'active', 'suspended', 'cancelled', 'exempt'])->default('trialing');
                $table->date('trial_ends_at')->nullable()->comment('Tanggal berakhir trial = created_at + plan.trial_months');
                $table->timestamps();

                try {
                    $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                    $table->foreign('plan_id')->references('id')->on('plans')->restrictOnDelete();
                } catch (\Throwable $e) {
                }
                $table->unique('tenant_id'); // 1 subscription per tenant
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('subscriptions');
    }
};
