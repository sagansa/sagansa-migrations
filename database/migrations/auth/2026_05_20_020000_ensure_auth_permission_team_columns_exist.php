<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    public function up(): void
    {
        $this->addTenantColumn('roles', after: 'guard_name');
        $this->addTenantColumn('permissions', after: 'guard_name');
        $this->addTenantColumn('model_has_roles', after: 'role_id');
        $this->addTenantColumn('model_has_permissions', after: 'permission_id');
    }

    public function down(): void
    {
        $this->dropTenantColumn('model_has_permissions');
        $this->dropTenantColumn('model_has_roles');
        $this->dropTenantColumn('permissions');
        $this->dropTenantColumn('roles');
    }

    private function addTenantColumn(string $table, ?string $after = null): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        if (!Schema::hasColumn($table, 'tenant_id')) {
            Schema::table($table, function (Blueprint $schema) use ($after) {
                $column = $schema->uuid('tenant_id')->nullable();

                if ($after !== null) {
                    $column->after($after);
                }
            });
        }

        $indexName = "{$table}_tenant_id_index";

        if (!$this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $schema) use ($indexName) {
                $schema->index('tenant_id', $indexName);
            });
        }
    }

    private function dropTenantColumn(string $table): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'tenant_id')) {
            return;
        }

        $indexName = "{$table}_tenant_id_index";

        Schema::table($table, function (Blueprint $schema) use ($table, $indexName) {
            if ($this->indexExists($table, $indexName)) {
                $schema->dropIndex($indexName);
            }

            $schema->dropColumn('tenant_id');
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        if (DB::connection($this->connection)->getDriverName() === 'sqlite') {
            return (bool) DB::connection($this->connection)->selectOne(
                "SELECT 1 FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?",
                [$table, $index]
            );
        }
        return DB::connection($this->connection)
            ->table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::connection($this->connection)->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $index)
            ->exists();
    }
};
