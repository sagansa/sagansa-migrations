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
        Schema::table('saved_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('saved_orders', 'tenant_id')) {
                            $table->uuid('tenant_id')->after('id')->nullable();            }
            try {
                            $table->index('tenant_id');            } catch (\Throwable $e) {
                // Constraint/index may already exist or may already be absent on partial migrations.
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saved_orders', function (Blueprint $table) {
            if (Schema::hasColumn('saved_orders', 'tenant_id')) {
                            $table->dropColumn('tenant_id');            }
        });
    }
};
