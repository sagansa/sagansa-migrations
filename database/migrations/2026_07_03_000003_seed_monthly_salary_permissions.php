<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $perms = [
            'view_panel::monthly::salary',
            'view_any_panel::monthly::salary',
            'create_panel::monthly::salary',
            'update_panel::monthly::salary',
            'delete_panel::monthly::salary',
            'delete_any_panel::monthly::salary'
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
