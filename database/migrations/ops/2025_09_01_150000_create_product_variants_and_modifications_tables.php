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
        if (!Schema::hasTable('product_variants')) {
                    Schema::create('product_variants', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('product_id');
                        $table->string('name');
                        $table->string('sku')->nullable();
                        $table->unsignedInteger('price')->nullable();
                        $table->unsignedInteger('stock')->default(0);
                        $table->boolean('is_active')->default(true);
                        $table->timestamps();

                        try {
                                                    $table->index('product_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->unique(['product_id', 'sku']);                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index(['product_id', 'is_active']);                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                    });
        }

        if (!Schema::hasTable('product_modifications')) {
                    Schema::create('product_modifications', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('product_id');
                        $table->string('name');
                        $table->unsignedInteger('price')->default(0);
                        $table->boolean('is_active')->default(true);
                        $table->timestamps();

                        try {
                                                    $table->index('product_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index(['product_id', 'is_active']);                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('product_modifications');
        Schema::dropIfExists('product_variants');
    }
};
