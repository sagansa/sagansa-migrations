<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('user_details')) {
            return;
        }

        if (! $this->foreignKeyExists('user_details', 'user_details_tenant_id_foreign')) {
            return;
        }

        DB::connection($this->connection)
            ->statement('ALTER TABLE user_details DROP FOREIGN KEY user_details_tenant_id_foreign');
    }

    public function down(): void
    {
        // No-op. Tenant records live in mysql_ops, so this FK must not be recreated in auth.
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        if (DB::connection($this->connection)->getDriverName() === 'sqlite') {
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
