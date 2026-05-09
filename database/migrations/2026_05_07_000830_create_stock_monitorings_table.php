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
        if (!Schema::hasTable('stock_monitorings')) {
            Schema::create('stock_monitorings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->integer('quantity_low');
            $table->unsignedBigInteger('unit_id');
            $table->string('category');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_monitorings');
    }
};
