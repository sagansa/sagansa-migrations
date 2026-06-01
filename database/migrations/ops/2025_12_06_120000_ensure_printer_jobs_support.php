<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('printer_jobs')) {
            return;
        }

        Schema::table('printer_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('printer_jobs', 'job_type')) {
                $table->string('job_type')->default('receipt')->after('order_id');
            }

            if (!Schema::hasColumn('printer_jobs', 'printed_at')) {
                $table->timestamp('printed_at')->nullable()->after('attempted_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('printer_jobs')) {
            return;
        }

        Schema::table('printer_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('printer_jobs', 'job_type')) {
                $table->dropColumn('job_type');
            }
        });
    }
};
