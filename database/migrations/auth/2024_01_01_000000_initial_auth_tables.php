<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Users Table
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('uuid')->nullable()->unique()->after('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->text('two_factor_secret')->nullable();
                $table->text('two_factor_recovery_codes')->nullable();
                $table->timestamp('two_factor_confirmed_at')->nullable();
                $table->unsignedBigInteger('current_team_id')->nullable();
                $table->text('profile_photo_path')->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        $isSqlite = DB::connection($this->connection)->getDriverName() === 'sqlite';

        if (!$isSqlite) {
            // 2. Permissions Table
            if (!Schema::hasTable('permissions')) {
                Schema::create('permissions', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name');
                    $table->string('guard_name');
                    $table->timestamps();
                });
            }

            // 3. Roles Table
            if (!Schema::hasTable('roles')) {
                Schema::create('roles', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name');
                    $table->string('guard_name');
                    $table->timestamps();
                });
            }

            // 4. Model Has Permissions
            if (!Schema::hasTable('model_has_permissions')) {
                Schema::create('model_has_permissions', function (Blueprint $table) {
                    $table->unsignedBigInteger('permission_id');
                    $table->string('model_type');
                    $table->unsignedBigInteger('model_id');

                    $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
                    $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                    $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
                });
            }

            // 5. Model Has Roles
            if (!Schema::hasTable('model_has_roles')) {
                Schema::create('model_has_roles', function (Blueprint $table) {
                    $table->unsignedBigInteger('role_id');
                    $table->string('model_type');
                    $table->unsignedBigInteger('model_id');

                    $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');
                    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                    $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
                });
            }

            // 6. Role Has Permissions
            if (!Schema::hasTable('role_has_permissions')) {
                Schema::create('role_has_permissions', function (Blueprint $table) {
                    $table->unsignedBigInteger('permission_id');
                    $table->unsignedBigInteger('role_id');

                    $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                    $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
                });
            }
        }

        // 7. Password Resets
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // 8. Sessions
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // 9. Personal Access Tokens
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('users');
    }
};
