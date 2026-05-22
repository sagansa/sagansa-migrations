<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('permissions')) {
            $now = now();
            $permissions = [
                [
                    'name' => 'menu_master_data',
                    'guard_name' => 'web',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'menu_system_settings',
                    'guard_name' => 'web',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ];

            foreach ($permissions as $permission) {
                $exists = DB::table('permissions')
                    ->where('name', $permission['name'])
                    ->where('guard_name', $permission['guard_name'])
                    ->exists();

                if (!$exists) {
                    if ($this->usesUuidPrimaryKey()) {
                        $permission['id'] = (string) Str::uuid();
                    }

                    DB::table('permissions')->insert($permission);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')
                ->whereIn('name', ['menu_master_data', 'menu_system_settings'])
                ->delete();
        }
    }

    private function usesUuidPrimaryKey(): bool
    {
        $idColumnType = Schema::getColumnType('permissions', 'id');

        return in_array($idColumnType, ['char', 'string', 'uuid'], true);
    }
};
