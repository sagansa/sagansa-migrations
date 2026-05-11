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
        Schema::table('saved_orders', function (Blueprint $table) {
            $table->uuid('tenant_id')->after('id')->nullable();
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saved_orders', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
    }
};
