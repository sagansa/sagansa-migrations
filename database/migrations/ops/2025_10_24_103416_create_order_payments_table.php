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
        if (!Schema::hasTable('order_payments')) {
                    Schema::create('order_payments', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('order_id');
                        $table->decimal('amount', 10, 2);
                        $table->uuid('payment_type_id');
                        $table->string('reference')->nullable();
                        $table->timestamp('captured_at')->nullable();
                        $table->boolean('is_offline')->default(false);
                        $table->timestamp('synced_at')->nullable();
                        $table->softDeletes();
                        $table->timestamps();
            
                        try {
                                                    $table->index('order_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('payment_type_id');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('order_payments');
    }
};
