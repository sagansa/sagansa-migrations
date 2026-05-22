<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tenant_user')) {
                    Schema::create('tenant_user', function (Blueprint $table) {
                        $table->uuid('tenant_id');
                        $table->uuid('user_id');
                        $table->string('role')->default('member');
                        $table->uuid('assigned_by')->nullable();
                        $table->timestamps();

                        $table->primary(['tenant_id', 'user_id']);
                        try {
                                                    $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->foreign('user_id')->references('uuid')->on('users')->cascadeOnDelete();                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->foreign('assigned_by')->references('uuid')->on('users')->nullOnDelete();                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('tenant_user');
    }
};
