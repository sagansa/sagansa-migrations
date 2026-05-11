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
        Schema::table('model_has_permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('model_has_permissions', 'tenant_id')) {
                $table->uuid('tenant_id')->nullable()->after('model_type');
            }
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });

        // Add tenant_id to model_has_roles if not exists
        Schema::table('model_has_roles', function (Blueprint $table) {
            if (!Schema::hasColumn('model_has_roles', 'tenant_id')) {
                $table->uuid('tenant_id')->nullable()->after('model_type');
            }
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
