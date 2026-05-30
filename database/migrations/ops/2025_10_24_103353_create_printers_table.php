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
        if (!Schema::hasTable('printers')) {
                    Schema::create('printers', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('tenant_id');
                        $table->uuid('store_id');
                        $table->string('name');
                        $table->enum('connection_type', ['wifi', 'bluetooth']);
                        $table->string('ip_address')->nullable();
                        $table->integer('port')->nullable();
                        $table->string('bluetooth_identifier')->nullable();
                        $table->boolean('is_active')->default(true);
                        $table->string('paper_size')->default('80mm');
                        $table->timestamps();
            
                        try {
                                                    $table->index('store_id');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                    });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
