<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncAuthData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sync-auth {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync authentication data from sagansa (mysql) to sagansa_user (mysql_auth)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('force') === false && app()->environment('production')) {
            if (!$this->confirm('You are in PRODUCTION. Do you really want to sync auth data? This will truncate the target auth tables!')) {
                return;
            }
        }

        $tables = [
            'permissions',
            'roles',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
        ];

        $this->info('Starting sync from sagansa to sagansa_user...');

        // Disable foreign key constraints on target
        DB::connection('mysql_auth')->statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            $this->info("Processing table: {$table}");

            // Check if source table exists
            if (!Schema::connection('mysql')->hasTable($table)) {
                $this->warn("Source table {$table} does not exist in sagansa. Skipping.");
                continue;
            }

            // Get data from source
            $data = DB::connection('mysql')->table($table)->get();
            
            if ($data->isEmpty()) {
                $this->line("Table {$table} is empty. Skipping.");
                continue;
            }

            // Truncate target table
            DB::connection('mysql_auth')->table($table)->truncate();

            // Insert data into target in chunks to avoid memory issues
            $chunks = array_chunk($data->map(fn($item) => (array)$item)->toArray(), 100);
            
            foreach ($chunks as $chunk) {
                DB::connection('mysql_auth')->table($table)->insert($chunk);
            }

            $this->info("Successfully synced " . $data->count() . " records for {$table}.");
        }

        // Re-enable foreign key constraints
        DB::connection('mysql_auth')->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Authentication data sync completed successfully!');
    }
}
