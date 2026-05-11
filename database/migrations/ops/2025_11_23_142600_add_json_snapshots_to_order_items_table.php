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
            $table->json('product_snapshot')->nullable();
            $table->json('variant_snapshot')->nullable();
            $table->json('modifications_snapshot')->nullable();
            
            // Make product_id nullable for new orders that only use snapshots
            $table->uuid('product_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['product_snapshot', 'variant_snapshot', 'modifications_snapshot']);
            // Note: We don't revert product_id back to non-nullable to avoid breaking existing data
        });
    }
};
