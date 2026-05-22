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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_type')) {
                            $table->enum('order_type', ['dine-in', 'takeaway'])->default('dine-in')->after('source');            }
            if (!Schema::hasColumn('orders', 'table_id')) {
                            $table->uuid('table_id')->nullable()->after('order_type');            }
            if (!Schema::hasColumn('orders', 'customer_type_id')) {
                            $table->uuid('customer_type_id')->nullable()->after('table_id');            }
        });

        if (!$this->foreignKeyExists('orders', 'orders_table_id_foreign')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
            });
        }

        if (!$this->foreignKeyExists('orders', 'orders_customer_type_id_foreign')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('customer_type_id')->references('id')->on('customer_types')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->foreignKeyExists('orders', 'orders_table_id_foreign')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['table_id']);
            });
        }

        if ($this->foreignKeyExists('orders', 'orders_customer_type_id_foreign')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['customer_type_id']);
            });
        }

        foreach (['order_type', 'table_id', 'customer_type_id'] as $column) {
            if (Schema::hasColumn('orders', $column)) {
                Schema::table('orders', function (Blueprint $table) use ($column) {
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
};
