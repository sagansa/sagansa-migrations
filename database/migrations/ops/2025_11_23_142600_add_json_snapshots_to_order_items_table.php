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
        Schema::table('order_items', function (Blueprint $table) {
            // Add JSON snapshot columns
            if (!Schema::hasColumn('order_items', 'product_snapshot')) {
                            $table->json('product_snapshot')->nullable();            }
            if (!Schema::hasColumn('order_items', 'variant_snapshot')) {
                            $table->json('variant_snapshot')->nullable();            }
            if (!Schema::hasColumn('order_items', 'modifications_snapshot')) {
                            $table->json('modifications_snapshot')->nullable();            }
            
            // Make product_id nullable for new orders that only use snapshots
            if (Schema::hasColumn('order_items', 'product_id')) {
                            $table->uuid('product_id')->nullable()->change();            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            foreach (['product_snapshot', 'variant_snapshot', 'modifications_snapshot'] as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $table->dropColumn($column);
                }
            }
            // Note: We don't revert product_id back to non-nullable to avoid breaking existing data
        });
    }
};
