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
        if (!Schema::hasTable('product_production')) {
            Schema::create('product_production', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->index('product_production_product_id_foreign');
            $table->unsignedBigInteger('production_id')->index('product_production_production_id_foreign');
            $table->decimal('quantity');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['production_id'])->references(['id'])->on('productions')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_production', function (Blueprint $table) {
            $table->dropForeign('product_production_product_id_foreign');
            $table->dropForeign('product_production_production_id_foreign');
        });
        Schema::dropIfExists('product_production');
    }
};
