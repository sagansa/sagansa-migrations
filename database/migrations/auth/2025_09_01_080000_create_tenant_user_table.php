<?php

use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    protected $connection = 'mysql_auth';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tenant membership is owned by apps/ops and lives in mysql_ops.tenant_user.
        // This auth migration is intentionally kept as a no-op for legacy deployments.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op. Do not drop legacy auth.tenant_user automatically.
    }
};
