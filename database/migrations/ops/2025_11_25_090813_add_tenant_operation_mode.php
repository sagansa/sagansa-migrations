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
        // Add operation mode to tenants
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('operation_mode', 20)->default('standard')->after('name');
            $table->json('foodcourt_config')->nullable()->after('operation_mode');
        });

        // Create table zones for better organization
        Schema::create('table_zones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_zones');
        
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['operation_mode', 'foodcourt_config']);
        });
    }
};
