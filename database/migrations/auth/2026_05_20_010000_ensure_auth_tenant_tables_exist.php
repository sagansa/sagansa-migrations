<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    public function up(): void
    {
        if (!Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->uuid('owner_id')->nullable()->unique();
                $table->string('operation_mode', 20)->default('standard');
                $table->json('foodcourt_config')->nullable();
                $table->timestamps();

                $table->foreign('owner_id')->references('uuid')->on('users')->cascadeOnDelete();
            });
        } else {
            $needsOwnerId = !Schema::connection('mysql_auth')->hasColumn('tenants', 'owner_id');
            $needsOperationMode = !Schema::connection('mysql_auth')->hasColumn('tenants', 'operation_mode');
            $needsFoodcourtConfig = !Schema::connection('mysql_auth')->hasColumn('tenants', 'foodcourt_config');

            Schema::table('tenants', function (Blueprint $table) use ($needsOwnerId, $needsOperationMode, $needsFoodcourtConfig) {
                if ($needsOwnerId) {
                    $table->uuid('owner_id')->nullable()->unique()->after('name');
                    $table->foreign('owner_id')->references('uuid')->on('users')->cascadeOnDelete();
                }

                if ($needsOperationMode) {
                    $table->string('operation_mode', 20)->default('standard')->after('name');
                }

                if ($needsFoodcourtConfig) {
                    $table->json('foodcourt_config')->nullable()->after('operation_mode');
                }
            });
        }

        if (!Schema::hasTable('tenant_user')) {
            Schema::create('tenant_user', function (Blueprint $table) {
                $table->uuid('tenant_id');
                $table->uuid('user_id');
                $table->string('role')->default('member');
                $table->uuid('assigned_by')->nullable();
                $table->timestamps();

                $table->primary(['tenant_id', 'user_id']);
                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                $table->foreign('user_id')->references('uuid')->on('users')->cascadeOnDelete();
                $table->foreign('assigned_by')->references('uuid')->on('users')->nullOnDelete();
            });
        }

        if (!Schema::hasTable('user_details')) {
            Schema::create('user_details', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
                $table->string('role')->default('staff');
                $table->boolean('is_active')->default(true);
                $table->uuid('manager_id')->nullable();
                $table->string('invitation_token')->nullable();
                $table->timestamp('invitation_token_expires_at')->nullable();
                $table->timestamp('invited_at')->nullable();
                $table->uuid('invited_by')->nullable();
                $table->string('verification_token')->nullable();
                $table->timestamps();

                $table->foreign('id')->references('uuid')->on('users')->cascadeOnDelete();
                $table->foreign('manager_id')->references('uuid')->on('users')->nullOnDelete();
                $table->foreign('invited_by')->references('uuid')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenants');
    }
};
