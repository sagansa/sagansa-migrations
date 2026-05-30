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
        if (!Schema::hasTable('product_variant_groups')) {
                    Schema::create('product_variant_groups', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('product_id');
                        $table->string('name');
                        $table->boolean('is_required')->default(false);
                        $table->integer('order')->default(0);
                        $table->timestamps();

                        try {
                                                    $table->index('product_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                    });
        }

        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'product_variant_group_id')) {
                            $table->uuid('product_variant_group_id')->nullable()->after('product_id');            }
        });

        if (!$this->foreignKeyExists('product_variants', 'product_variants_product_variant_group_id_foreign')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->index('product_variant_group_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->foreignKeyExists('product_variants', 'product_variants_product_variant_group_id_foreign')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropForeign(['product_variant_group_id']);
            });
        }

        if (Schema::hasColumn('product_variants', 'product_variant_group_id')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropColumn('product_variant_group_id');
            });
        }

        Schema::dropIfExists('product_variant_groups');
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
