<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if ($this->foreignKeyExists('order_items', 'order_items_product_id_foreign')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        }

        if ($this->foreignKeyExists('order_items', 'order_items_product_variant_id_foreign')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['product_variant_id']);
            });
        }

        foreach (['product_id', 'product_variant_id', 'name_snapshot'] as $column) {
            if (Schema::hasColumn('order_items', $column)) {
                Schema::table('order_items', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Restore columns if rollback is needed
            if (!Schema::hasColumn('order_items', 'product_id')) {
                            $table->uuid('product_id')->nullable()->after('order_id');            }
            if (!Schema::hasColumn('order_items', 'product_variant_id')) {
                            $table->uuid('product_variant_id')->nullable()->after('product_id');            }
            if (!Schema::hasColumn('order_items', 'name_snapshot')) {
                            $table->string('name_snapshot')->nullable()->after('product_variant_id');            }
        });

        if (!$this->foreignKeyExists('order_items', 'order_items_product_id_foreign')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->index('product_id');
            });
        }

        if (!$this->foreignKeyExists('order_items', 'order_items_product_variant_id_foreign')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->index('product_variant_id');
            });
        }
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        if (DB::connection($this->connection)->getDriverName() === 'sqlite') {
            $column = str_replace([$table . '_', '_foreign'], '', $constraint);
            try {
                $foreignKeys = DB::connection($this->connection)->select("PRAGMA foreign_key_list({$table})");
                foreach ($foreignKeys as $fk) {
                    $fkArray = (array) $fk;
                    $fromField = $fkArray['from'] ?? $fkArray['FROM'] ?? null;
                    if ($fromField === $column) {
                        return true;
                    }
                }
            } catch (\Throwable $e) {
            }
            return false;
        }
        return (bool) DB::connection($this->connection)->selectOne(
            <<<'SQL'
            SELECT 1
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND CONSTRAINT_NAME = ?
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            LIMIT 1
            SQL,
            [$table, $constraint]
        );
    }
};
