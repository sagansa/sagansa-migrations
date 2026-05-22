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
        if (!Schema::connection('mysql_auth')->hasColumn('tenants', 'owner_id')) {
            Schema::connection('mysql_auth')->table('tenants', function (Blueprint $table) {
                $table->uuid('owner_id')->nullable()->after('name');
                try {
                                    $table->unique('owner_id');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }
                try {
                                    $table->foreign('owner_id')->references('uuid')->on('users')->cascadeOnDelete();                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }
            });
        }

        if (!Schema::hasTable('stores')) {
                    Schema::create('stores', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('tenant_id');
                        $table->string('name');
                        $table->string('location')->nullable();
                        $table->timestamps();

                        try {
                                                    $table->index(['tenant_id', 'name']);                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('stores');

        if (Schema::connection('mysql_auth')->hasColumn('tenants', 'owner_id')) {
            Schema::connection('mysql_auth')->table('tenants', function (Blueprint $table) {
                try {
                                    $table->dropForeign('tenants_owner_id_foreign');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }
                try {
                                    $table->dropUnique('tenants_owner_id_unique');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }
                $table->dropColumn('owner_id');
            });
        }
    }
};
