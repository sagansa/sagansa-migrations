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
        // Track if order contains items from multiple stores
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_multi_store')->default(false)->after('store_id');
        });

        // Track store for each order item (critical for food court)
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignUuid('store_id')->nullable()->after('order_id')->constrained()->cascadeOnDelete();
        });


        // Populate store_id for existing order_items from their orders
        DB::statement('
            UPDATE order_items 
            INNER JOIN orders ON order_items.order_id = orders.id
            SET order_items.store_id = orders.store_id
            WHERE order_items.store_id IS NULL
        ');

        // Make store_id required for order_items going forward
        Schema::table('order_items', function (Blueprint $table) {
            $table->uuid('store_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_multi_store');
        });
    }
};
