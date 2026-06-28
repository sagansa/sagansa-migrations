<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Subscription & Billing — Soft-delete pada stores
 *
 * Store TIDAK boleh di-hard-delete agar omzet historis tetap ada untuk perhitungan
 * billing & audit trail. Hapus store -> soft-delete (set deleted_at).
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $needsSoftDelete = ! Schema::connection($this->connection)->hasColumn('stores', 'deleted_at');

        if ($needsSoftDelete) {
            Schema::connection($this->connection)->table('stores', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasSoftDelete = Schema::connection($this->connection)->hasColumn('stores', 'deleted_at');

        if ($hasSoftDelete) {
            Schema::connection($this->connection)->table('stores', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
