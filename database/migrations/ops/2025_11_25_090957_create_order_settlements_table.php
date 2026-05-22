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
        if (!Schema::hasTable('order_settlements')) {
                    Schema::create('order_settlements', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
                        $table->foreignUuid('store_id')->constrained()->cascadeOnDelete();
            
                        // Financial breakdown
                        $table->decimal('gross_amount', 10, 2);           // Subtotal for this store
                        $table->decimal('tenant_fee_percentage', 5, 2);   // Fee percentage
                        $table->decimal('tenant_fee_amount', 10, 2);      // Calculated fee
                        $table->decimal('net_amount', 10, 2);             // Amount to store (gross - fee)
            
                        // Settlement status
                        $table->string('status', 20)->default('pending'); // 'pending', 'settled', 'disputed'
                        $table->date('settlement_date')->nullable();
                        $table->timestamp('settled_at')->nullable();
            
                        $table->text('notes')->nullable();
                        $table->timestamps();
            
                        // Indexes
                        try {
                                                    $table->index('order_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('store_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('status');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('settlement_date');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('order_settlements');
    }
};
