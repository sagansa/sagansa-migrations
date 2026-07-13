<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $isSqlite = DB::connection($this->connection)->getDriverName() === 'sqlite';

            // SQLite stores FK definitions inline in the table schema and cannot
            // drop a column that a FK references (table-rebuild would be required).
            // For the test DB, skip dropping presence_id and the named FK; keep
            // the nullable column (harmless for tests). New columns are still added.
            Schema::table('monthly_salaries', function (Blueprint $table) use ($isSqlite) {
                if (! $isSqlite && Schema::hasColumn('monthly_salaries', 'presence_id')) {
                    $table->dropForeign('monthly_salaries_presence_id_foreign');
                    $table->dropColumn('presence_id');
                }

                // Add new columns
                $table->uuid('tenant_id')->nullable()->after('id')->index();
                $table->unsignedBigInteger('user_id')->nullable()->after('tenant_id')->index();
                $table->date('period_start')->nullable()->after('user_id');
                $table->date('period_end')->nullable()->after('period_start');
                $table->integer('total_work_days')->default(0)->after('period_end');
                $table->decimal('total_hours', 8, 2)->default(0)->after('total_work_days');
                $table->decimal('base_salary', 15, 2)->default(0)->after('total_hours');
                $table->json('allowances')->nullable()->after('base_salary');
                $table->json('deductions')->nullable()->after('allowances');
                $table->decimal('total_salary', 15, 2)->default(0)->after('deductions');
                $table->tinyInteger('status')->default(1)->after('total_salary');
                $table->dateTime('payment_date')->nullable()->after('status');

                // Foreign key constraint if tenants table exists
                if (Schema::hasTable('tenants')) {
                    $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
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
                // Drop added columns
                $table->dropForeign(['tenant_id']);
                $table->dropColumn([
                    'tenant_id',
                    'user_id',
                    'period_start',
                    'period_end',
                    'total_work_days',
                    'total_hours',
                    'base_salary',
                    'allowances',
                    'deductions',
                    'total_salary',
                    'status',
                    'payment_date'
                ]);

                // Re-add presence_id
                $table->unsignedBigInteger('presence_id')->nullable()->index();
                $table->foreign(['presence_id'])->references(['id'])->on('presences')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }
};
