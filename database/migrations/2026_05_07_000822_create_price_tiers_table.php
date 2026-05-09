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
        if (!Schema::hasTable('price_tiers')) {
            Schema::create('price_tiers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('product_id');
            $table->integer('min_quantity');
            $table->integer('max_quantity');
            $table->bigInteger('price');
            $table->string('label')->nullable();
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_tiers');
    }
};
