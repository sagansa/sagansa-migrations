<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        if (Schema::hasTable('monthly_salaries')) {
            Schema::table('monthly_salaries', function (Blueprint $table) {
                if (!Schema::hasColumn('monthly_salaries', 'paid_amount')) {
                    $table->decimal('paid_amount', 15, 2)->nullable()->after('amount');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('monthly_salaries')) {
            Schema::table('monthly_salaries', function (Blueprint $table) {
                if (Schema::hasColumn('monthly_salaries', 'paid_amount')) {
                    $table->dropColumn('paid_amount');
                }
            });
        }
    }
};
