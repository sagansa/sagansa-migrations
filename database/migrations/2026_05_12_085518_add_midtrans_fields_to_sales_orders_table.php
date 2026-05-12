<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales_orders', function (Blueprint $blueprint) {
            if (!Schema::hasColumn('sales_orders', 'payment_method')) {
                $blueprint->string('payment_method')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('sales_orders', 'admin_fee')) {
                $blueprint->decimal('admin_fee', 15, 2)->default(0)->after('shipping_cost');
            }
            if (!Schema::hasColumn('sales_orders', 'snap_token')) {
                $blueprint->string('snap_token')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('sales_orders', 'midtrans_transaction_id')) {
                $blueprint->string('midtrans_transaction_id')->nullable()->after('snap_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['payment_method', 'admin_fee', 'snap_token', 'midtrans_transaction_id']);
        });
    }
};
