<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $teams = config('permission.teams');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        throw_if(empty($tableNames), new Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.'));
        throw_if($teams && empty($columnNames['team_foreign_key'] ?? null), new Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.'));

        if (!Schema::hasTable($tableNames['permissions'])) {
            Schema::create($tableNames['permissions'], static function (Blueprint $table) {
                // $table->engine('InnoDB');
                $table->uuid('id')->primary(); // permission id
                $table->string('name');       // For MyISAM use string('name', 225); // (or 166 for InnoDB with Redundant/Compact row format)
                $table->string('guard_name'); // For MyISAM use string('guard_name', 25);
                $table->timestamps();

                try {
                                    $table->unique(['name', 'guard_name']);                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }
            });
        }

        if (!Schema::hasTable($tableNames['roles'])) {
            Schema::create($tableNames['roles'], static function (Blueprint $table) use ($teams, $columnNames) {
                // $table->engine('InnoDB');
                $table->uuid('id')->primary(); // role id
                if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                    $table->uuid($columnNames['team_foreign_key'])->nullable();
                    try {
                                            $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');                    } catch (\Throwable $e) {
                        // Constraint/index may already exist or may already be absent on partial migrations.
                    }
                }
                $table->string('name');       // For MyISAM use string('name', 225); // (or 166 for InnoDB with Redundant/Compact row format)
                $table->string('guard_name'); // For MyISAM use string('guard_name', 25);
                $table->timestamps();
                if ($teams || config('permission.testing')) {
                    try {
                                            $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);                    } catch (\Throwable $e) {
                        // Constraint/index may already exist or may already be absent on partial migrations.
                    }
                } else {
                    try {
                                            $table->unique(['name', 'guard_name']);                    } catch (\Throwable $e) {
                        // Constraint/index may already exist or may already be absent on partial migrations.
                    }
                }
            });
        }

        if (!Schema::hasTable($tableNames['model_has_permissions'])) {
            Schema::create($tableNames['model_has_permissions'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $teams) {
                $table->uuid($pivotPermission);

                $table->string('model_type');
                $table->uuid($columnNames['model_morph_key']);
                try {
                                    $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }

                try {
                                    $table->foreign($pivotPermission)
                                        ->references('id') // permission id
                                        ->on($tableNames['permissions'])
                                        ->onDelete('cascade');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }
                if ($teams) {
                    $table->uuid($columnNames['team_foreign_key'])->nullable();
                    try {
                                            $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');                    } catch (\Throwable $e) {
                        // Constraint/index may already exist or may already be absent on partial migrations.
                    }

                    $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                        'model_has_permissions_permission_model_type_primary');
                } else {
                    $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                        'model_has_permissions_permission_model_type_primary');
                }

            });
        }

        if (!Schema::hasTable($tableNames['model_has_roles'])) {
            Schema::create($tableNames['model_has_roles'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams) {
                $table->uuid($pivotRole);

                $table->string('model_type');
                $table->uuid($columnNames['model_morph_key']);
                try {
                                    $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }

                try {
                                    $table->foreign($pivotRole)
                                        ->references('id') // role id
                                        ->on($tableNames['roles'])
                                        ->onDelete('cascade');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }
                if ($teams) {
                    $table->uuid($columnNames['team_foreign_key'])->nullable();
                    try {
                                            $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');                    } catch (\Throwable $e) {
                        // Constraint/index may already exist or may already be absent on partial migrations.
                    }

                    $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                        'model_has_roles_role_model_type_primary');
                } else {
                    $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                        'model_has_roles_role_model_type_primary');
                }
            });
        }

        if (!Schema::hasTable($tableNames['role_has_permissions'])) {
            Schema::create($tableNames['role_has_permissions'], static function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission) {
                $table->uuid($pivotPermission);
                $table->uuid($pivotRole);

                try {
                                    $table->foreign($pivotPermission)
                                        ->references('id') // permission id
                                        ->on($tableNames['permissions'])
                                        ->onDelete('cascade');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }

                try {
                                    $table->foreign($pivotRole)
                                        ->references('id') // role id
                                        ->on($tableNames['roles'])
                                        ->onDelete('cascade');                } catch (\Throwable $e) {
                    // Constraint/index may already exist or may already be absent on partial migrations.
                }

                $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
            });
        }

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
    }
};
