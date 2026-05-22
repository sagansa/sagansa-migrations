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
        Schema::table('attendances', function (Blueprint $table) {
            // Tambahkan field untuk store check-in dan check-out terpisah
            if (!Schema::hasColumn('attendances', 'check_in_store_id')) {
                            $table->uuid('check_in_store_id')->after('store_id');            }
            if (!Schema::hasColumn('attendances', 'check_out_store_id')) {
                            $table->uuid('check_out_store_id')->nullable()->after('check_in_store_id');            }
        });

        if (!$this->foreignKeyExists('attendances', 'attendances_check_in_store_id_foreign')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreign('check_in_store_id')->references('id')->on('stores')->cascadeOnDelete();
            });
        }

        if (!$this->foreignKeyExists('attendances', 'attendances_check_out_store_id_foreign')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreign('check_out_store_id')->references('id')->on('stores')->nullOnDelete();
            });
        }

        if (!$this->indexExists('attendances', 'attendances_check_in_store_id_index')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index('check_in_store_id');
            });
        }

        if (!$this->indexExists('attendances', 'attendances_check_out_store_id_index')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index('check_out_store_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->foreignKeyExists('attendances', 'attendances_check_in_store_id_foreign')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropForeign(['check_in_store_id']);
            });
        }

        if ($this->foreignKeyExists('attendances', 'attendances_check_out_store_id_foreign')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropForeign(['check_out_store_id']);
            });
        }

        foreach (['check_in_store_id', 'check_out_store_id'] as $column) {
            if (Schema::hasColumn('attendances', $column)) {
                Schema::table('attendances', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
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
