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
        Schema::table('product_modifications', function (Blueprint $table) {
            if (!Schema::hasColumn('product_modifications', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
                $table->index(['product_id', 'sort_order']);
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
                $table->index(['product_variant_group_id', 'sort_order']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_modifications', function (Blueprint $table) {
            if (Schema::hasColumn('product_modifications', 'sort_order')) {
                $table->dropIndex(['product_id', 'sort_order']);
                $table->dropColumn('sort_order');
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'sort_order')) {
                $table->dropIndex(['product_variant_group_id', 'sort_order']);
                $table->dropColumn('sort_order');
            }
        });
    }
};