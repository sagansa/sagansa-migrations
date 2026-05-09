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
        if (!Schema::hasTable('stock_monitoring_details')) {
            Schema::create('stock_monitoring_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('stock_monitoring_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('coefficient');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_monitoring_details');
    }
};
