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
        if (!$this->foreignKeyExists('printer_jobs', 'printer_jobs_order_id_foreign')) {
            Schema::table('printer_jobs', function (Blueprint $table) {
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->foreignKeyExists('printer_jobs', 'printer_jobs_order_id_foreign')) {
            Schema::table('printer_jobs', function (Blueprint $table) {
                $table->dropForeign(['order_id']);
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
