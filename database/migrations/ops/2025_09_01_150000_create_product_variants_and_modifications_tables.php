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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->string('name');
            $table->string('sku')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->unique(['product_id', 'sku']);
            $table->index(['product_id', 'is_active']);
        });

        Schema::create('product_modifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->string('name');
            $table->unsignedInteger('price')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->index(['product_id', 'is_active']);
        });
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
