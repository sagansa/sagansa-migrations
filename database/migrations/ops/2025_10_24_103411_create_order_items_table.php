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
        if (!Schema::hasTable('order_items')) {
                    Schema::create('order_items', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('order_id');
                        $table->uuid('product_id');
                        $table->uuid('product_variant_id')->nullable(); // nullable for default product
                        $table->string('name_snapshot'); // snapshot to ensure historical accuracy
                        $table->integer('quantity')->default(1);
                        $table->decimal('unit_price', 10, 2);
                        $table->decimal('total_price', 10, 2);
                        $table->text('notes')->nullable();
                        $table->timestamps();
            
                        try {
                                                    $table->index('order_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('product_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('product_variant_id');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('order_items');
    }
};
