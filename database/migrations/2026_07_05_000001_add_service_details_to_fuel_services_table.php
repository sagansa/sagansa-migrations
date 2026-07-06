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
        if (Schema::hasTable('fuel_services')) {
            Schema::table('fuel_services', function (Blueprint $table) {
                if (!Schema::hasColumn('fuel_services', 'service_details')) {
                    $table->json('service_details')->nullable()->after('notes');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('fuel_services')) {
            Schema::table('fuel_services', function (Blueprint $table) {
                if (Schema::hasColumn('fuel_services', 'service_details')) {
                    $table->dropColumn('service_details');
                }
            });
        }
    }
};
