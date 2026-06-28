<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Subscription & Billing — Tambah field ke tabel tenants
 *
 * billing_exempt      : TRUE = tenant tidak pernah kena billing (internal/pilot/partner)
 * subscription_status : cache status untuk query cepat (none/trialing/active/suspended)
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $needsBillingExempt = ! Schema::connection($this->connection)->hasColumn('tenants', 'billing_exempt');
        $needsSubscriptionStatus = ! Schema::connection($this->connection)->hasColumn('tenants', 'subscription_status');

        if (! $needsBillingExempt && ! $needsSubscriptionStatus) {
            return;
        }

        Schema::connection($this->connection)->table('tenants', function (Blueprint $table) use ($needsBillingExempt, $needsSubscriptionStatus) {
            if ($needsBillingExempt) {
                $table->boolean('billing_exempt')->default(false)->after('foodcourt_config');
                $table->index('billing_exempt');
            }

            if ($needsSubscriptionStatus) {
                $table->string('subscription_status', 20)->default('none')->after('billing_exempt');
                $table->index('subscription_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasBillingExempt = Schema::connection($this->connection)->hasColumn('tenants', 'billing_exempt');
        $hasSubscriptionStatus = Schema::connection($this->connection)->hasColumn('tenants', 'subscription_status');

        if (! $hasBillingExempt && ! $hasSubscriptionStatus) {
            return;
        }

        Schema::connection($this->connection)->table('tenants', function (Blueprint $table) use ($hasSubscriptionStatus, $hasBillingExempt) {
            if ($hasSubscriptionStatus) {
                // drop index dulu bila ada
                try {
                    $table->dropIndex(['subscription_status']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('subscription_status');
            }
            if ($hasBillingExempt) {
                try {
                    $table->dropIndex(['billing_exempt']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('billing_exempt');
            }
        });
    }
};
