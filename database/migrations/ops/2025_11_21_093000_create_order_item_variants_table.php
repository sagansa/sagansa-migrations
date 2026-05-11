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
        Schema::create('order_item_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_item_id');
            $table->uuid('product_variant_id');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->timestamps();

            $table->foreign('order_item_id')->references('id')->on('order_items')->cascadeOnDelete();
            $table->foreign('product_variant_id')->references('id')->on('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_variants');
    }
};
