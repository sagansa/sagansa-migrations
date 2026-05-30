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
        if (!Schema::hasTable('order_item_variants')) {
                    Schema::create('order_item_variants', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('order_item_id');
                        $table->uuid('product_variant_id');
                        $table->string('name');
                        $table->decimal('price', 15, 2);
                        $table->timestamps();

                        try {
                                                    $table->index('order_item_id');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('order_item_variants');
    }
};
