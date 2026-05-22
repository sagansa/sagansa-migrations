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
        if (!Schema::hasTable('tables')) {
                    Schema::create('tables', function (Blueprint $table) {
                        $table->uuid('id')->primary();
                        $table->uuid('store_id');
                        $table->string('table_number');
                        $table->boolean('is_available')->default(true);
                        $table->integer('capacity')->nullable();
                        $table->timestamps();
            
                        try {
                                                    $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->unique(['store_id', 'table_number']);                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('tables');
    }
};
