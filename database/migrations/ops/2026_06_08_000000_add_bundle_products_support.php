<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        $schema->table('products', function (Blueprint $table) use ($schema) {
            if (! $schema->hasColumn('products', 'type')) {
                $table->string('type', 20)->default('single')->after('tenant_id');
            }

            if (! $schema->hasColumn('products', 'bundle_pricing_mode')) {
                $table->string('bundle_pricing_mode', 20)->default('fixed')->after('type');
            }
        });

        if (! $schema->hasTable('product_bundle_items')) {
            $schema->create('product_bundle_items', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('bundle_product_id');
                $table->uuid('component_product_id');
                $table->unsignedInteger('quantity')->default(1);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['bundle_product_id', 'component_product_id'], 'product_bundle_items_unique_component');
                $table->index('bundle_product_id');
                $table->index('component_product_id');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        $schema->dropIfExists('product_bundle_items');

        $schema->table('products', function (Blueprint $table) use ($schema) {
            if ($schema->hasColumn('products', 'bundle_pricing_mode')) {
                $table->dropColumn('bundle_pricing_mode');
            }

            if ($schema->hasColumn('products', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
