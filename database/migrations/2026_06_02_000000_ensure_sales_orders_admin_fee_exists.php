<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sales_orders') || Schema::hasColumn('sales_orders', 'admin_fee')) {
            return;
        }

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->decimal('admin_fee', 15, 2)->default(0)->after('shipping_cost');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('sales_orders') || !Schema::hasColumn('sales_orders', 'admin_fee')) {
            return;
        }

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('admin_fee');
        });
    }
};
