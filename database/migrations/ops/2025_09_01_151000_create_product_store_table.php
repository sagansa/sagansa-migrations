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
        if (!Schema::hasTable('product_store')) {
                    Schema::create('product_store', function (Blueprint $table) {
                        $table->uuid('product_id');
                        $table->uuid('store_id');
                        $table->timestamps();

                        $table->primary(['product_id', 'store_id']);

                        try {
                                                    $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('product_store');
    }
};
