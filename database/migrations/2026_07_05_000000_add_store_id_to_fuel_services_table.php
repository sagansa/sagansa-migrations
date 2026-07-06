<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable strict SQL modes to handle incorrect '0000-00-00' dates in existing records
        DB::statement("SET SESSION sql_mode = ''");

        if (Schema::hasTable('fuel_services')) {
            Schema::table('fuel_services', function (Blueprint $table) {
                if (!Schema::hasColumn('fuel_services', 'store_id')) {
                    $table->unsignedBigInteger('store_id')->nullable()->after('vehicle_id');
                    $table->foreign('store_id', 'fuel_services_store_id_foreign')->references('id')->on('stores')->onDelete('set null');
                }
            });

            // Backfill from closing stores that are linked via the pivot table
            if (Schema::hasTable('closing_store_fuel_service') && Schema::hasTable('closing_stores')) {
                DB::table('fuel_services')
                    ->join('closing_store_fuel_service', 'fuel_services.id', '=', 'closing_store_fuel_service.fuel_service_id')
                    ->join('closing_stores', 'closing_store_fuel_service.closing_store_id', '=', 'closing_stores.id')
                    ->update(['fuel_services.store_id' => DB::raw('closing_stores.store_id')]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (Schema::hasTable('fuel_services')) {
            Schema::table('fuel_services', function (Blueprint $table) {
                if (Schema::hasColumn('fuel_services', 'store_id')) {
                    $table->dropForeign('fuel_services_store_id_foreign');
                    $table->dropColumn('store_id');
                }
            });
        }
    }
};
