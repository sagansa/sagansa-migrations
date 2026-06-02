<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('stores') || Schema::hasColumn('stores', 'daily_salary_amount')) {
            return;
        }

        Schema::table('stores', function (Blueprint $table) {
            $table->decimal('daily_salary_amount', 15, 2)->default(25000)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('stores') || !Schema::hasColumn('stores', 'daily_salary_amount')) {
            return;
        }

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('daily_salary_amount');
        });
    }
};
