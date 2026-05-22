<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'was_late')) {
                $table->boolean('was_late')->default(false)->after('status');
            }
            if (!Schema::hasColumn('attendances', 'auto_checked_out_at')) {
                $table->timestamp('auto_checked_out_at')->nullable()->after('check_out');
            }
            if (!Schema::hasColumn('attendances', 'gps_accuracy')) {
                $table->decimal('gps_accuracy', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('attendances', 'device_info')) {
                $table->string('device_info', 255)->nullable();
            }
            if (!Schema::hasColumn('attendances', 'ip_address')) {
                $table->string('ip_address', 45)->nullable();
            }
            if (!Schema::hasColumn('attendances', 'is_within_range')) {
                $table->boolean('is_within_range')->default(true);
            }
            if (!Schema::hasColumn('attendances', 'distance_to_store')) {
                $table->decimal('distance_to_store', 10, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $columns = ['was_late', 'auto_checked_out_at', 'gps_accuracy', 'device_info', 'ip_address', 'is_within_range', 'distance_to_store'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('attendances', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
