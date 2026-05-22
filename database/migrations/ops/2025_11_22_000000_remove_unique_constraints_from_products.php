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
        foreach (['products_slug_unique', 'products_sku_unique'] as $index) {
            if ($this->indexExists('products', $index)) {
                Schema::table('products', function (Blueprint $table) use ($index) {
                    $table->dropUnique($index);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!$this->indexExists('products', 'products_slug_unique')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unique('slug', 'products_slug_unique');
            });
        }

        if (!$this->indexExists('products', 'products_sku_unique')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unique('sku', 'products_sku_unique');
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
};
