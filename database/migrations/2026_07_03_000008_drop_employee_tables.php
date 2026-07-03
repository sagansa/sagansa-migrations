<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop foreign keys referencing employees table
        if (Schema::hasTable('savings')) {
            try {
                Schema::table('savings', function (Blueprint $table) {
                    $table->dropForeign('savings_employee_id_foreign');
                });
            } catch (\Exception $e) {}
        }

        if (Schema::hasTable('contract_employees')) {
            try {
                Schema::table('contract_employees', function (Blueprint $table) {
                    $table->dropForeign('contract_employees_employee_id_foreign');
                });
            } catch (\Exception $e) {}
        }

        if (Schema::hasTable('working_experiences')) {
            try {
                Schema::table('working_experiences', function (Blueprint $table) {
                    $table->dropForeign('working_experiences_employee_id_foreign');
                });
            } catch (\Exception $e) {}
        }

        if (Schema::hasTable('movement_asset_results')) {
            try {
                Schema::table('movement_asset_results', function (Blueprint $table) {
                    $table->dropForeign('movement_asset_results_executor_id_foreign');
                });
            } catch (\Exception $e) {}
            try {
                Schema::table('movement_asset_results', function (Blueprint $table) {
                    $table->dropForeign('movement_asset_results_supervisor_id_foreign');
                });
            } catch (\Exception $e) {}
        }

        // 2. Drop the tables
        Schema::dropIfExists('working_experiences');
        Schema::dropIfExists('contract_employees');
        Schema::dropIfExists('savings');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('contract_locations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for cleanup
    }
};
