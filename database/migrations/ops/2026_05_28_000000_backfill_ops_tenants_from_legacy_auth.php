<?php

use Illuminate\Database\Migrations\Migration;
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
        if (! Schema::connection('mysql_ops')->hasTable('tenants')) {
            return;
        }

        if (! Schema::connection('mysql_auth')->hasTable('tenants')) {
            return;
        }

        $legacyTenants = DB::connection('mysql_auth')
            ->table('tenants')
            ->get();

        foreach ($legacyTenants as $tenant) {
            DB::connection('mysql_ops')
                ->table('tenants')
                ->updateOrInsert(
                    ['id' => $tenant->id],
                    [
                        'name' => $tenant->name,
                        'owner_id' => $tenant->owner_id ?? null,
                        'operation_mode' => $tenant->operation_mode ?? 'standard',
                        'foodcourt_config' => $tenant->foodcourt_config ?? null,
                        'created_at' => $tenant->created_at ?? now(),
                        'updated_at' => $tenant->updated_at ?? now(),
                    ],
                );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally keep ops tenants. The legacy auth table may already be gone.
    }
};
