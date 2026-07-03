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
        if (Schema::hasTable('daily_salaries')) {
            Schema::table('daily_salaries', function (Blueprint $table) {
                if (!Schema::hasColumn('daily_salaries', 'monthly_salary_id')) {
                    $table->unsignedBigInteger('monthly_salary_id')->nullable()->after('presence_id')->index();
                    $table->foreign('monthly_salary_id')->references('id')->on('monthly_salaries')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('daily_salaries')) {
            Schema::table('daily_salaries', function (Blueprint $table) {
                if (Schema::hasColumn('daily_salaries', 'monthly_salary_id')) {
                    $table->dropForeign(['monthly_salary_id']);
                    $table->dropColumn('monthly_salary_id');
                }
            });
        }
    }
};
