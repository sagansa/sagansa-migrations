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

        if (! $schema->hasTable('product_modifications')) {
            return;
        }

        $schema->table('product_modifications', function (Blueprint $table) use ($schema) {
            if (! $schema->hasColumn('product_modifications', 'linked_product_id')) {
                $table->uuid('linked_product_id')->nullable()->after('is_active');
                $table->index('linked_product_id');
            }

            if (! $schema->hasColumn('product_modifications', 'linked_product_quantity')) {
                $table->unsignedInteger('linked_product_quantity')->nullable()->after('linked_product_id');
            }
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('product_modifications')) {
            return;
        }

        $schema->table('product_modifications', function (Blueprint $table) use ($schema) {
            if ($schema->hasColumn('product_modifications', 'linked_product_id')) {
                $table->dropIndex(['linked_product_id']);
                $table->dropColumn('linked_product_id');
            }

            if ($schema->hasColumn('product_modifications', 'linked_product_quantity')) {
                $table->dropColumn('linked_product_quantity');
            }
        });
    }
};
