<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Subscription & Billing — Tabel: plans
 *
 * Template pricing SAGANSA. Fully editable super-admin (bisa naik cap 99rb -> 149rb, dll).
 * Saat invoice dibuat, snapshot harga disalin ke billing_cycles.snapshot_plan (immutable).
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('plans')) {
            Schema::connection($this->connection)->create('plans', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('code', 50)->unique()->comment('mis. sagansa_default');
                $table->string('name');
                $table->decimal('pos_rate_percent', 5, 4)->default(0.0100)->comment('Tarif omzet POS, mis. 0.0100 = 1%');
                $table->unsignedInteger('pos_base_charge')->default(99000)->comment('Cap maksimal charge POS per store (sebelum diskon)');
                $table->unsignedInteger('attendance_rate')->default(5000)->comment('Tarif per karyawan aktif /bulan');
                $table->unsignedInteger('attendance_free_count')->default(5)->comment('Jumlah karyawan pertama yang gratis');
                $table->unsignedInteger('trial_months')->default(3)->comment('Durasi trial tenant baru (bulan)');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('plans');
    }
};
