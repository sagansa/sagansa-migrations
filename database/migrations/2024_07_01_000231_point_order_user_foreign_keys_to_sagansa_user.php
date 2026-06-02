<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    protected $connection = 'mysql';

    private array $foreignKeys = [
        ['carts', 'user_id', 'carts_user_id_foreign'],
        ['delivery_addresses', 'user_id', 'delivery_addresses_ibfk_1'],
        ['sales_orders', 'ordered_by_id', 'sales_orders_ibfk_6'],
        ['sales_orders', 'assigned_by_id', 'sales_orders_ibfk_7'],
        ['products', 'user_id', 'products_user_id_foreign'],
    ];

    public function up(): void
    {
        $authDatabase = $this->authDatabase();

        foreach ($this->foreignKeys as [$table, $column, $constraint]) {
            $this->dropForeignIfExists($table, $constraint);
            $this->normalizeZeroDateTimes($table);
            $this->addForeign($table, $column, $constraint, $authDatabase);
        }
    }

    public function down(): void
    {
        $mainDatabase = DB::connection('mysql')->getDatabaseName();

        foreach ($this->foreignKeys as [$table, $column, $constraint]) {
            $this->dropForeignIfExists($table, $constraint);
            $this->normalizeZeroDateTimes($table);
            $this->addForeign($table, $column, $constraint, $mainDatabase);
        }
    }

    private function authDatabase(): string
    {
        return config('database.connections.mysql_auth.database', 'sagansa_user');
    }

    private function dropForeignIfExists(string $table, string $constraint): void
    {
        $exists = DB::connection('mysql')
            ->table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::connection('mysql')->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraint)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();

        if ($exists) {
            DB::connection('mysql')->statement(
                sprintf('ALTER TABLE `%s` DROP FOREIGN KEY `%s`', $table, $constraint)
            );
        }
    }

    private function addForeign(string $table, string $column, string $constraint, string $referencedDatabase): void
    {
        try {
            DB::connection('mysql')->statement(sprintf(
                'ALTER TABLE `%s` ADD CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
                $table,
                $constraint,
                $column,
                str_replace('`', '``', $referencedDatabase),
            ));
        } catch (QueryException $exception) {
            if ($this->isReferencesPrivilegeDenied($exception)) {
                Log::warning('Skipping cross-database user foreign key because MySQL user lacks REFERENCES privilege.', [
                    'table' => $table,
                    'column' => $column,
                    'constraint' => $constraint,
                    'referenced_database' => $referencedDatabase,
                    'message' => $exception->getMessage(),
                ]);

                return;
            }

            throw $exception;
        }
    }

    private function isReferencesPrivilegeDenied(QueryException $exception): bool
    {
        $errorInfo = $exception->errorInfo;

        return ($errorInfo[1] ?? null) === 1142
            || str_contains($exception->getMessage(), 'REFERENCES command denied');
    }

    private function normalizeZeroDateTimes(string $table): void
    {
        $columns = DB::connection('mysql')
            ->table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::connection('mysql')->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->whereIn('DATA_TYPE', ['date', 'datetime', 'timestamp'])
            ->get(['COLUMN_NAME', 'DATA_TYPE', 'IS_NULLABLE']);

        foreach ($columns as $column) {
            $zeroValue = $column->DATA_TYPE === 'date'
                ? '0000-00-00'
                : '0000-00-00 00:00:00';

            $replacement = $column->IS_NULLABLE === 'YES'
                ? 'NULL'
                : ($column->DATA_TYPE === 'date' ? 'CURRENT_DATE()' : 'CURRENT_TIMESTAMP()');

            DB::connection('mysql')->statement(sprintf(
                'UPDATE `%s` SET `%s` = %s WHERE `%s` = ?',
                $table,
                $column->COLUMN_NAME,
                $replacement,
                $column->COLUMN_NAME,
            ), [$zeroValue]);
        }
    }
};
