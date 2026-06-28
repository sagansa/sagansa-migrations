<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Subscription & Billing — Tabel: plan_discounts
 *
 * Diskon yang berlaku untuk plan. CRUD via super-admin.
 * Default: diskon 30% untuk POS -> efektif cap Rp 69.300 (dari 99.000).
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('plan_discounts')) {
            Schema::connection($this->connection)->create('plan_discounts', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('plan_id');
                $table->string('code', 50)->comment('mis. LAUNCH30');
                $table->string('name');
                $table->enum('type', ['percentage', 'fixed'])->default('percentage');
                $table->decimal('value', 10, 2)->comment('30 untuk 30%, atau 30000 untuk nominal fixed');
                $table->enum('applies_to', ['pos', 'attendance', 'total'])->default('pos');
                $table->date('starts_at');
                $table->date('ends_at')->nullable()->comment('NULL = tanpa batas akhir');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                try {
                    $table->foreign('plan_id')->references('id')->on('plans')->cascadeOnDelete();
                } catch (\Throwable $e) {
                }
                $table->index(['plan_id', 'is_active']);
                $table->index('code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('plan_discounts');
    }
};
