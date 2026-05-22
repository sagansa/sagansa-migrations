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
        // Add operation mode to tenants
        Schema::connection('mysql_auth')->table('tenants', function (Blueprint $table) {
            if (!Schema::connection('mysql_auth')->hasColumn('tenants', 'operation_mode')) {
                $table->string('operation_mode', 20)->default('standard')->after('name');
            }
            if (!Schema::connection('mysql_auth')->hasColumn('tenants', 'foodcourt_config')) {
                $table->json('foodcourt_config')->nullable()->after('operation_mode');
            }
        });

        // Create table zones for better organization
        if (!Schema::hasTable('table_zones')) {
                    Schema::create('table_zones', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('tenant_id');
                        $table->string('name', 100);
                        $table->text('description')->nullable();
                        $table->integer('display_order')->default(0);
                        $table->boolean('is_active')->default(true);
                        $table->timestamps();
            
                        try {
                                                    $table->index('tenant_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                    });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_zones');
        
        Schema::connection('mysql_auth')->table('tenants', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::connection('mysql_auth')->hasColumn('tenants', 'operation_mode')) {
                $columnsToDrop[] = 'operation_mode';
            }
            if (Schema::connection('mysql_auth')->hasColumn('tenants', 'foodcourt_config')) {
                $columnsToDrop[] = 'foodcourt_config';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
