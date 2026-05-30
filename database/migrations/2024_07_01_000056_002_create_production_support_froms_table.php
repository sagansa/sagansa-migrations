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
        if (!Schema::hasTable('production_support_froms')) {
            Schema::create('production_support_froms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('production_support_froms_product_id_foreign');
            $table->decimal('quantity');
            $table->unsignedBigInteger('production_id')->index('production_support_froms_production_id_foreign');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_support_froms');
    }
};
