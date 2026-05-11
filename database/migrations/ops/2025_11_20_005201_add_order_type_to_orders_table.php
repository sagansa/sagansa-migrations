<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type', ['dine-in', 'takeaway'])->default('dine-in')->after('source');
            $table->uuid('table_id')->nullable()->after('order_type');
            $table->uuid('customer_type_id')->nullable()->after('table_id');
            
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
            $table->foreign('customer_type_id')->references('id')->on('customer_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropForeign(['customer_type_id']);
            $table->dropColumn(['order_type', 'table_id', 'customer_type_id']);
        });
    }
};
