<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah plans.pos_usage_threshold — omzet bulanan minimum agar Attendance
 * dianggap gratis (Opsi A "pakai POS"). Default Rp1.000.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('plans')) {
            return;
        }

        if (! $schema->hasColumn('plans', 'pos_usage_threshold')) {
            $schema->table('plans', function (Blueprint $table) {
                $table->unsignedInteger('pos_usage_threshold')->default(1000)
                    ->after('pos_base_charge')
                    ->comment('Omzet bulanan minimum agar Attendance gratis (mis. 1000 = Rp1.000)');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('plans')) {
            return;
        }

        if ($schema->hasColumn('plans', 'pos_usage_threshold')) {
            $schema->table('plans', function (Blueprint $table) {
                $table->dropColumn('pos_usage_threshold');
            });
        }
    }
};
