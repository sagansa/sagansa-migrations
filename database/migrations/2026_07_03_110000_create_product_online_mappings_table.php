<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('product_online_mappings')) {
            Schema::create('product_online_mappings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('product_id')->index();
                $table->unsignedBigInteger('online_shop_provider_id')->index();
                $table->string('online_sku')->index();
                $table->string('online_item_id')->nullable();
                $table->string('online_variation_id')->nullable();
                $table->timestamps();

                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
                
                $table->foreign('online_shop_provider_id')
                    ->references('id')
                    ->on('online_shop_providers')
                    ->onDelete('cascade');

                // Mencegah duplikasi mapping SKU untuk provider yang sama
                $table->unique(['online_shop_provider_id', 'online_sku'], 'unique_provider_sku');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_online_mappings');
    }
};
