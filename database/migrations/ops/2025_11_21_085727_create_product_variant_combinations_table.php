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
        Schema::create('product_variant_combinations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->string('sku')->nullable()->unique();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            
            // Store combination as JSON array of variant IDs
            $table->json('variant_ids');
            
            // Denormalized display name for easier querying
            $table->string('name')->nullable();
            
            $table->timestamps();
            
            // Foreign key
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
            
            // Indexes
            $table->index('product_id');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_combinations');
    }
};
