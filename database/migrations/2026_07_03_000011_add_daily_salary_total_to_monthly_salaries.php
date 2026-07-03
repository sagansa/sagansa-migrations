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
        if (Schema::hasTable('monthly_salaries')) {
            Schema::table('monthly_salaries', function (Blueprint $table) {
                if (!Schema::hasColumn('monthly_salaries', 'daily_salary_total')) {
                    $table->decimal('daily_salary_total', 15, 2)->default(0)->after('base_salary');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('monthly_salaries')) {
            Schema::table('monthly_salaries', function (Blueprint $table) {
                if (Schema::hasColumn('monthly_salaries', 'daily_salary_total')) {
                    $table->dropColumn('daily_salary_total');
                }
            });
        }
    }
};
