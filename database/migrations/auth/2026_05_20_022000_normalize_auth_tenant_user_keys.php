<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    public function up(): void
    {
        if (DB::connection($this->connection)->getDriverName() === 'sqlite') {
            return;
        }

        if (!Schema::hasTable('users') || !Schema::hasTable('tenants')) {
            return;
        }

        DB::connection($this->connection)->statement("
            UPDATE tenants t
            JOIN users u ON t.owner_id = CAST(u.id AS CHAR)
            LEFT JOIN tenants existing_owner ON existing_owner.owner_id = u.uuid
            SET t.owner_id = u.uuid
            WHERE u.uuid IS NOT NULL
              AND existing_owner.id IS NULL
        ");

        if (Schema::hasTable('tenant_user')) {
            DB::connection($this->connection)->statement("
                UPDATE tenant_user tu
                JOIN users u ON tu.user_id = CAST(u.id AS CHAR)
                LEFT JOIN tenant_user existing_member
                    ON existing_member.tenant_id = tu.tenant_id
                    AND existing_member.user_id = u.uuid
                SET tu.user_id = u.uuid
                WHERE u.uuid IS NOT NULL
                  AND existing_member.user_id IS NULL
            ");

            DB::connection($this->connection)->statement("
                UPDATE tenant_user tu
                JOIN users u ON tu.assigned_by = CAST(u.id AS CHAR)
                SET tu.assigned_by = u.uuid
                WHERE u.uuid IS NOT NULL
            ");

            DB::connection($this->connection)->statement("
                INSERT INTO tenant_user (tenant_id, user_id, role, assigned_by, created_at, updated_at)
                SELECT t.id, t.owner_id, 'owner', t.owner_id, NOW(), NOW()
                FROM tenants t
                JOIN users u ON u.uuid = t.owner_id
                LEFT JOIN tenant_user tu
                    ON tu.tenant_id = t.id
                    AND tu.user_id = t.owner_id
                WHERE tu.user_id IS NULL
            ");
        }

        if (Schema::hasTable('user_details')) {
            DB::connection($this->connection)->statement("
                UPDATE user_details ud
                JOIN users u ON ud.id = CAST(u.id AS CHAR)
                LEFT JOIN user_details existing_detail ON existing_detail.id = u.uuid
                SET ud.id = u.uuid
                WHERE u.uuid IS NOT NULL
                  AND existing_detail.id IS NULL
            ");

            DB::connection($this->connection)->statement("
                UPDATE user_details ud
                JOIN users u ON ud.manager_id = CAST(u.id AS CHAR)
                SET ud.manager_id = u.uuid
                WHERE u.uuid IS NOT NULL
            ");

            DB::connection($this->connection)->statement("
                UPDATE user_details ud
                JOIN users u ON ud.invited_by = CAST(u.id AS CHAR)
                SET ud.invited_by = u.uuid
                WHERE u.uuid IS NOT NULL
            ");
        }
    }

    public function down(): void
    {
        //
    }
};
