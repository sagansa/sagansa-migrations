<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        if (!Schema::hasTable('sales_orders')) {
            return;
        }

        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'payment_proof_printed_at')) {
                $table->timestamp('payment_proof_printed_at')->nullable()->after('image_payment');
            }

            if (!Schema::hasColumn('sales_orders', 'payment_proof_print_count')) {
                $table->unsignedInteger('payment_proof_print_count')->default(0)->after('payment_proof_printed_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('sales_orders')) {
            return;
        }

        Schema::table('sales_orders', function (Blueprint $table) {
            foreach (['payment_proof_print_count', 'payment_proof_printed_at'] as $column) {
                if (Schema::hasColumn('sales_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
