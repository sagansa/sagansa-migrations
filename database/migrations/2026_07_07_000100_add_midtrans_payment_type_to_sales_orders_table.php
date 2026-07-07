<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'midtrans_payment_type')) {
                $table->string('midtrans_payment_type')->nullable()->after('midtrans_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            if (Schema::hasColumn('sales_orders', 'midtrans_payment_type')) {
                $table->dropColumn('midtrans_payment_type');
            }
        });
    }
};
