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
        if (!Schema::hasTable('customer_types')) {
                    Schema::create('customer_types', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('store_id');
                        $table->string('name');
                        $table->boolean('is_active')->default(true);
                        $table->integer('order')->default(0);
                        $table->timestamps();
            
                        try {
                                                    $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('customer_types');
    }
};
