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
        if (Schema::hasTable('invoice_purchases') && !Schema::hasColumn('invoice_purchases', 'sales_order_id')) {
            Schema::table('invoice_purchases', function (Blueprint $table) {
                // Kaitan invoice -> sales order (link/unlink dari app mobile).
                $table->unsignedBigInteger('sales_order_id')->nullable()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('invoice_purchases') && Schema::hasColumn('invoice_purchases', 'sales_order_id')) {
            Schema::table('invoice_purchases', function (Blueprint $table) {
                $table->dropColumn('sales_order_id');
            });
        }
    }
};
