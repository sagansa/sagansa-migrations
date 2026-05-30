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
        if (!Schema::hasTable('products')) {
                    Schema::create('products', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('tenant_id')->nullable();
                        $table->uuid('unit_id')->nullable();
                        $table->uuid('category_id')->nullable();
                        $table->uuid('user_id')->nullable();
                        $table->string('name');
                        $table->string('slug')->unique();
                        $table->text('description')->nullable();
                        $table->unsignedInteger('price');
                        $table->string('image')->nullable();
                        $table->string('sku')->unique();
                        $table->string('barcode')->nullable()->unique();
                        $table->unsignedInteger('stock')->default(0);
                        $table->boolean('request')->default(true);
                        $table->boolean('remaining')->default(true);
                        $table->boolean('is_active')->default(true);
                        $table->timestamps();
                        $table->softDeletes();

                        try {
                                                    $table->index('unit_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('category_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->index('user_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }

                        try {
                                                    $table->index(['tenant_id']);                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('products');
    }
};
