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
        if (!Schema::hasTable('customers')) {
                    Schema::create('customers', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('tenant_id');
                        $table->string('name')->nullable();
                        $table->string('email')->nullable();
                        $table->string('phone')->nullable();
                        $table->text('address')->nullable();
                        $table->timestamps();

                        try {
                                                    $table->index('tenant_id');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('customers');
    }
};
