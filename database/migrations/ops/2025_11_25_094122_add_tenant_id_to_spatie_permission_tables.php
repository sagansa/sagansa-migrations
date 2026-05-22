<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add tenant_id to model_has_permissions if not exists
        if (!Schema::hasColumn('model_has_permissions', 'tenant_id')) {
            Schema::table('model_has_permissions', function (Blueprint $table) {
                $table->uuid('tenant_id')->nullable()->after('model_type');
            });
        }

        // Add index separately so duplicate-key errors can be caught
        try {
            Schema::table('model_has_permissions', function (Blueprint $table) {
                $table->index('tenant_id');
            });
        } catch (\Throwable $e) {
            // Index may already exist from a previous partial migration run.
        }

        // Add tenant_id to model_has_roles if not exists
        if (!Schema::hasColumn('model_has_roles', 'tenant_id')) {
            Schema::table('model_has_roles', function (Blueprint $table) {
                $table->uuid('tenant_id')->nullable()->after('model_type');
            });
        }

        // Add index separately so duplicate-key errors can be caught
        try {
            Schema::table('model_has_roles', function (Blueprint $table) {
                $table->index('tenant_id');
            });
        } catch (\Throwable $e) {
            // Index may already exist from a previous partial migration run.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_permissions', function (Blueprint $table) {
            try {
                            $table->dropIndex(['tenant_id']);            } catch (\Throwable $e) {
                // Constraint/index may already exist or may already be absent on partial migrations.
            }
            if (Schema::hasColumn('model_has_permissions', 'tenant_id')) {
                            $table->dropColumn('tenant_id');            }
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            try {
                            $table->dropIndex(['tenant_id']);            } catch (\Throwable $e) {
                // Constraint/index may already exist or may already be absent on partial migrations.
            }
            if (Schema::hasColumn('model_has_roles', 'tenant_id')) {
                            $table->dropColumn('tenant_id');            }
        });
    }
};
