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
        if (!Schema::hasTable('order_item_modifications')) {
                    Schema::create('order_item_modifications', function (Blueprint $table) {
                        $table->id();
                        $table->uuid('order_item_id');
                        $table->uuid('product_modification_id');
                        $table->decimal('price', 10, 2); // snapshot price
                        $table->integer('quantity')->default(1); // optional quantity
                        $table->timestamps();
            
                        try {
                                                    $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');                        } catch (\Throwable $e) {
                            // Constraint/index may already exist or may already be absent on partial migrations.
                        }
                        try {
                                                    $table->foreign('product_modification_id')->references('id')->on('product_modifications')->onDelete('cascade');                        } catch (\Throwable $e) {
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
        Schema::dropIfExists('order_item_modifications');
    }
};
