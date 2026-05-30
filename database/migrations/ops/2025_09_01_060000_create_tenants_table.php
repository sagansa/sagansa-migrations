<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('tenants')) {
            Schema::connection($this->connection)->create('tenants', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->uuid('owner_id')->nullable();
                $table->string('operation_mode', 20)->default('standard');
                $table->json('foodcourt_config')->nullable();
                $table->timestamps();

                $table->index('owner_id');
                $table->index('operation_mode');
            });

            return;
        }

        $needsOwnerId = ! Schema::connection('mysql_ops')->hasColumn('tenants', 'owner_id');
        $needsOperationMode = ! Schema::connection('mysql_ops')->hasColumn('tenants', 'operation_mode');
        $needsFoodcourtConfig = ! Schema::connection('mysql_ops')->hasColumn('tenants', 'foodcourt_config');

        if (! $needsOwnerId && ! $needsOperationMode && ! $needsFoodcourtConfig) {
            return;
        }

        Schema::connection($this->connection)->table('tenants', function (Blueprint $table) use ($needsOwnerId, $needsOperationMode, $needsFoodcourtConfig) {
            if ($needsOwnerId) {
                $table->uuid('owner_id')->nullable()->after('name');
                $table->index('owner_id');
            }

            if ($needsOperationMode) {
                $table->string('operation_mode', 20)->default('standard')->after('owner_id');
                $table->index('operation_mode');
            }

            if ($needsFoodcourtConfig) {
                $table->json('foodcourt_config')->nullable()->after('operation_mode');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('tenants');
    }
};
