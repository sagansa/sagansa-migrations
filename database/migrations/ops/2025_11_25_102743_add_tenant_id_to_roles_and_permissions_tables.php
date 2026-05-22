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
        if (!Schema::hasColumn('roles', 'tenant_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->uuid('tenant_id')->nullable()->after('guard_name');
            });
        }

        if (!$this->indexExists('roles', 'roles_tenant_id_index')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->index('tenant_id', 'roles_tenant_id_index');
            });
        }

        if (!Schema::hasColumn('permissions', 'tenant_id')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->uuid('tenant_id')->nullable()->after('guard_name');
            });
        }

        if (!$this->indexExists('permissions', 'permissions_tenant_id_index')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->index('tenant_id', 'permissions_tenant_id_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('roles', 'tenant_id')) {
            Schema::table('roles', function (Blueprint $table) {
                if ($this->indexExists('roles', 'roles_tenant_id_index')) {
                    $table->dropIndex('roles_tenant_id_index');
                }

                $table->dropColumn('tenant_id');
            });
        }

        if (Schema::hasColumn('permissions', 'tenant_id')) {
            Schema::table('permissions', function (Blueprint $table) {
                if ($this->indexExists('permissions', 'permissions_tenant_id_index')) {
                    $table->dropIndex('permissions_tenant_id_index');
                }

                $table->dropColumn('tenant_id');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $connection = DB::connection($this->connection);

        if ($connection->getDriverName() === 'sqlite') {
            return (bool) $connection->selectOne(
                "SELECT 1 FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?",
                [$table, $index]
            );
        }

        return $connection
            ->table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', $connection->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $index)
            ->exists();
    }
};
