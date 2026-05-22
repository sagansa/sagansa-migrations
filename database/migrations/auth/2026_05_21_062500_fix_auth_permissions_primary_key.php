<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        DB::connection($this->connection)
            ->table('permissions')
            ->whereNull('id')
            ->delete();

        if (!$this->hasPrimaryKey('permissions')) {
            DB::connection($this->connection)->statement(
                'ALTER TABLE permissions MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY'
            );
        } else {
            DB::connection($this->connection)->statement(
                'ALTER TABLE permissions MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT'
            );
        }
    }

    public function down(): void
    {
        // Keep the corrected Spatie schema; reverting would break permission inserts.
    }

    private function hasPrimaryKey(string $table): bool
    {
        return DB::connection($this->connection)
            ->table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::connection($this->connection)->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_TYPE', 'PRIMARY KEY')
            ->exists();
    }
};
