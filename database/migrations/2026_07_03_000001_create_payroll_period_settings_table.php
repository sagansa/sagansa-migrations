<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('payroll_period_settings')) {
            Schema::create('payroll_period_settings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('tenant_id')->index();
                $table->integer('start_day')->default(1);
                $table->integer('end_day')->default(31);
                $table->decimal('transport_allowance_per_day', 15, 2)->default(25000);
                $table->decimal('meal_allowance_per_day', 15, 2)->default(20000);
                $table->decimal('late_penalty_per_hour', 15, 2)->default(10000);
                $table->decimal('no_checkout_penalty', 15, 2)->default(20000);
                $table->timestamps();

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
        Schema::dropIfExists('payroll_period_settings');
    }
};
