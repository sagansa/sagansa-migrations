<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Subscription & Billing — Seed default plan, diskon, dan billing_settings
 *
 * Default values:
 *   - Plan "sagansa_default": POS 1% / cap 99.000, attendance 5.000/karyawan, 5 free, trial 3 bulan
 *   - Diskon "LAUNCH30": 30% untuk POS (efektif cap Rp 69.300)
 *   - billing_settings: provider xendit aktif (API key kosong, perlu diisi super-admin)
 *
 * Catatan: ini adalah data seed via migration. Super-admin bisa edit semua via UI.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        // 1. Default plan
        $planId = (string) Str::uuid();
        $existingPlan = DB::connection($this->connection)->table('plans')->where('code', 'sagansa_default')->first();

        if (! $existingPlan) {
            DB::connection($this->connection)->table('plans')->insert([
                'id' => $planId,
                'code' => 'sagansa_default',
                'name' => 'SAGANSA Default',
                'pos_rate_percent' => 0.0100,        // 1%
                'pos_base_charge' => 99000,          // cap Rp 99.000
                'attendance_rate' => 5000,           // Rp 5.000/karyawan
                'attendance_free_count' => 5,        // 5 karyawan pertama gratis
                'trial_months' => 3,                 // 3 bulan trial
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $planId = $existingPlan->id;
        }

        // 2. Default diskon (LAUNCH30 — 30% untuk POS)
        $existingDiscount = DB::connection($this->connection)->table('plan_discounts')
            ->where('plan_id', $planId)
            ->where('code', 'LAUNCH30')
            ->first();

        if (! $existingDiscount) {
            DB::connection($this->connection)->table('plan_discounts')->insert([
                'id' => (string) Str::uuid(),
                'plan_id' => $planId,
                'code' => 'LAUNCH30',
                'name' => 'Launch Promo 30%',
                'type' => 'percentage',
                'value' => 30,
                'applies_to' => 'pos',
                'starts_at' => $now->toDateString(),
                'ends_at' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 3. billing_settings (singleton — 1 baris)
        $existingSettings = DB::connection($this->connection)->table('billing_settings')->first();

        if (! $existingSettings) {
            DB::connection($this->connection)->table('billing_settings')->insert([
                'id' => (string) Str::uuid(),
                'active_provider' => 'xendit',
                'xendit_secret_key' => null,
                'xendit_verify_key' => null,
                'midtrans_server_key' => null,
                'midtrans_client_key' => null,
                'midtrans_is_production' => false,
                'webhook_secret' => Str::random(40),
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection($this->connection)->table('plan_discounts')->where('code', 'LAUNCH30')->delete();
        DB::connection($this->connection)->table('plans')->where('code', 'sagansa_default')->delete();
        DB::connection($this->connection)->table('billing_settings')->truncate();
    }
};
