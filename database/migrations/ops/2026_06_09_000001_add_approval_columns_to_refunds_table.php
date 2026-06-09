<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            if (!Schema::hasColumn('refunds', 'approved_by')) {
                $table->uuid('approved_by')->nullable()->after('refunded_at')->index();
            }

            if (!Schema::hasColumn('refunds', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }

            if (!Schema::hasColumn('refunds', 'rejected_by')) {
                $table->uuid('rejected_by')->nullable()->after('approved_at')->index();
            }

            if (!Schema::hasColumn('refunds', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }

            if (!Schema::hasColumn('refunds', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $columns = array_values(array_filter([
                Schema::hasColumn('refunds', 'approved_by') ? 'approved_by' : null,
                Schema::hasColumn('refunds', 'approved_at') ? 'approved_at' : null,
                Schema::hasColumn('refunds', 'rejected_by') ? 'rejected_by' : null,
                Schema::hasColumn('refunds', 'rejected_at') ? 'rejected_at' : null,
                Schema::hasColumn('refunds', 'rejection_reason') ? 'rejection_reason' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
