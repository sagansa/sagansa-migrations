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
            if (!Schema::hasColumn('sales_orders', 'shipping_payment_method')) {
                $table->string('shipping_payment_method')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('sales_orders', 'midtrans_snap_token')) {
                $table->string('midtrans_snap_token')->nullable()->after('shipping_payment_method');
            }

            if (!Schema::hasColumn('sales_orders', 'midtrans_status')) {
                $table->string('midtrans_status')->nullable()->after('midtrans_snap_token');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('sales_orders')) {
            return;
        }

        Schema::table('sales_orders', function (Blueprint $table) {
            foreach (['shipping_payment_method', 'midtrans_snap_token', 'midtrans_status'] as $column) {
                if (Schema::hasColumn('sales_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
