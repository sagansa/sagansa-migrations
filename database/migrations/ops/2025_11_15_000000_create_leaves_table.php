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
        if (!Schema::hasTable('leaves')) {
                    Schema::create('leaves', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('tenant_id');
                        $table->uuid('user_id');
                        $table->string('type');
                        $table->date('start_date');
                        $table->date('end_date');
                        $table->unsignedInteger('duration')->nullable();
                        $table->text('reason')->nullable();
                        $table->string('status', 50)->default('pending');
                        $table->uuid('approved_by_id')->nullable();
                        $table->timestamp('approved_at')->nullable();
                        $table->timestamp('rejected_at')->nullable();
                        $table->text('review_notes')->nullable();
                        $table->timestamps();

                        try {
                                                    $table->index('user_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('approved_by_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index(['tenant_id', 'status']);                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index(['tenant_id', 'user_id']);                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('leaves');
    }
};
