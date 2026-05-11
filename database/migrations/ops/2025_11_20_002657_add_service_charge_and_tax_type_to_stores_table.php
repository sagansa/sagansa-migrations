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
        Schema::table('stores', function (Blueprint $table) {
            $table->enum('tax_type', ['exclusive', 'inclusive'])->default('exclusive')->after('tax_name');
            $table->enum('service_charge_type', ['percentage', 'fixed'])->default('percentage')->after('tax_type');
            $table->decimal('service_charge_rate', 5, 2)->nullable()->after('service_charge_type');
            $table->decimal('service_charge_amount', 15, 2)->nullable()->after('service_charge_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['tax_type', 'service_charge_type', 'service_charge_rate', 'service_charge_amount']);
        });
    }
};
