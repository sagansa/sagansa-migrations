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
        Schema::dropIfExists('saved_orders');
        
        if (!Schema::hasTable('saved_orders')) {
                    Schema::create('saved_orders', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('store_id');
                        $table->uuid('user_id')->nullable();
                        $table->string('name');
                        $table->json('items');
                        $table->decimal('total', 15, 2);
                        $table->string('order_type')->nullable();
                        $table->uuid('table_id')->nullable();
                        $table->uuid('customer_type_id')->nullable();
                        $table->text('notes')->nullable();
                        $table->timestamps();

                        try {
                                                    $table->index('store_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('user_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('table_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('customer_type_id');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('saved_orders');
    }
};
