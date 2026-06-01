<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('product_prices')) {
            return;
        }

        Schema::connection($this->connection)->create('product_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('store_id');
            $table->uuid('product_id');
            $table->uuid('variant_id')->nullable();
            $table->uuid('customer_type_id');
            $table->unsignedBigInteger('price');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('store_id');
            $table->index('product_id');
            $table->index('variant_id');
            $table->index('customer_type_id');
            $table->unique(
                ['store_id', 'product_id', 'variant_id', 'customer_type_id'],
                'product_prices_scope_unique'
            );
        });

        try {
            Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
                $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
                $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
                $table->foreign('variant_id')->references('id')->on('product_variant_combinations')->nullOnDelete();
                $table->foreign('customer_type_id')->references('id')->on('customer_types')->cascadeOnDelete();
            });
        } catch (Throwable) {
            // Shared hosting sometimes rejects FK creation. Indexes still keep lookups fast.
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('product_prices');
    }
};
