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
            // Drop foreign keys first
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_variant_id']);
            
            // Drop deprecated columns - we now use snapshots exclusively
            $table->dropColumn(['product_id', 'product_variant_id', 'name_snapshot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Restore columns if rollback is needed
            $table->uuid('product_id')->nullable()->after('order_id');
            $table->uuid('product_variant_id')->nullable()->after('product_id');
            $table->string('name_snapshot')->nullable()->after('product_variant_id');
            
            // Restore foreign keys
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->nullOnDelete();
        });
    }
};
