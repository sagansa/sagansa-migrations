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
        if (!Schema::hasTable('orders')) {
                    Schema::create('orders', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('tenant_id');
                        $table->uuid('store_id');
                        $table->string('created_by')->nullable(); // user or device
                        $table->string('customer_name')->nullable();
                        $table->string('table_code')->nullable();
                        $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('pending');
                        $table->decimal('subtotal', 10, 2)->default(0);
                        $table->decimal('discount_total', 10, 2)->default(0);
                        $table->decimal('tax_total', 10, 2)->default(0);
                        $table->decimal('service_total', 10, 2)->default(0);
                        $table->decimal('grand_total', 10, 2)->default(0);
                        $table->uuid('payment_type_id')->nullable();
                        $table->timestamp('paid_at')->nullable();
                        $table->enum('source', ['pos', 'web-order'])->default('pos');
                        $table->string('device_identifier')->nullable();
                        $table->boolean('is_offline')->default(false);
                        $table->timestamp('synced_at')->nullable();
                        $table->softDeletes();
                        $table->timestamps();
            
                        try {
                                                    $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->foreign('payment_type_id')->references('id')->on('payment_type')->onDelete('set null');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('orders');
    }
};
