<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $perms = [
            'view_panel::salary::penalty',
            'view_any_panel::salary::penalty',
            'create_panel::salary::penalty',
            'update_panel::salary::penalty',
            'delete_panel::salary::penalty',
            'delete_any_panel::salary::penalty',

            'view_panel::employee::loan',
            'view_any_panel::employee::loan',
            'create_panel::employee::loan',
            'update_panel::employee::loan',
            'delete_panel::employee::loan',
            'delete_any_panel::employee::loan'
        ];

        $now = Carbon::now();

        // 1. Seed permissions
        foreach (['web', 'api'] as $guard) {
            foreach ($perms as $p) {
                $exists = DB::connection($this->connection)
                    ->table('permissions')
                    ->where('name', $p)
                    ->where('guard_name', $guard)
                    ->exists();

                if (!$exists) {
                    DB::connection($this->connection)->table('permissions')->insert([
                        'name' => $p,
                        'guard_name' => $guard,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                }
            }
        }

        // 2. Assign permissions to roles
        $roles = DB::connection($this->connection)
            ->table('roles')
            ->whereIn('name', ['admin', 'super_admin', 'super-admin'])
            ->get();

        foreach ($roles as $role) {
            // Find permissions with matching guard
            $dbPerms = DB::connection($this->connection)
                ->table('permissions')
                ->where('guard_name', $role->guard_name)
                ->whereIn('name', $perms)
                ->get();

            foreach ($dbPerms as $perm) {
                $linkExists = DB::connection($this->connection)
                    ->table('role_has_permissions')
                    ->where('role_id', $role->id)
                    ->where('permission_id', $perm->id)
                    ->exists();

                if (!$linkExists) {
                    DB::connection($this->connection)->table('role_has_permissions')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $perm->id
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional rollback logic
    }
};
