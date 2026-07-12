<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('product_prices')) {
            return;
        }

        $hasVariantColumn = Schema::connection($this->connection)->hasColumn('product_prices', 'variant_id');

        if ($hasVariantColumn) {
            DB::connection($this->connection)
                ->table('product_prices')
                ->whereNotNull('variant_id')
                ->delete();
        }

        $rows = DB::connection($this->connection)
            ->table('product_prices')
            ->orderByDesc('updated_at')
            ->get();

        $seen = [];
        foreach ($rows as $row) {
            $key = "{$row->store_id}:{$row->product_id}:{$row->customer_type_id}";
            if (isset($seen[$key])) {
                DB::connection($this->connection)->table('product_prices')->where('id', $row->id)->delete();
                continue;
            }
            $seen[$key] = true;
        }

        // Blueprint's dropForeign()/dropUnique() are deferred-executed, so a try/catch
        // around the call cannot catch errors thrown at commit time. Pre-check instead,
        // matching the foreignKeyExists()/indexExists() pattern used in sibling migrations.
        if (Schema::connection($this->connection)->hasColumn('product_prices', 'variant_id')
            && $this->foreignKeyExists('product_prices', 'product_prices_variant_id_foreign')) {
            Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
                $table->dropForeign(['variant_id']);
            });
        }

        if ($this->indexExists('product_prices', 'product_prices_scope_unique')) {
            Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
                $table->dropUnique('product_prices_scope_unique');
            });
        }

        if (Schema::connection($this->connection)->hasColumn('product_prices', 'variant_id')) {
            Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
                $table->dropColumn('variant_id');
            });
        }

        if (! $this->indexExists('product_prices', 'product_prices_store_product_type_unique')) {
            Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
                $table->unique(
                    ['store_id', 'product_id', 'customer_type_id'],
                    'product_prices_store_product_type_unique'
                );
            });
        }
    }

    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasTable('product_prices')) {
            return;
        }

        if (! Schema::connection($this->connection)->hasColumn('product_prices', 'variant_id')) {
            Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
                $table->uuid('variant_id')->nullable()->after('product_id');
                $table->index('variant_id');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        if (DB::connection($this->connection)->getDriverName() === 'sqlite') {
            return (bool) DB::connection($this->connection)->selectOne(
                "SELECT 1 FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?",
                [$table, $index]
            );
        }
        return (bool) DB::connection($this->connection)->selectOne(
            <<<'SQL'
            SELECT 1
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND INDEX_NAME = ?
            LIMIT 1
            SQL,
            [$table, $index]
        );
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
            } catch (\Throwable) {
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
