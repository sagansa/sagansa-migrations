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
        if (!Schema::hasTable('product_self_consumption')) {
            Schema::create('product_self_consumption', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->index('product_self_consumption_product_id_foreign');
            $table->unsignedBigInteger('self_consumption_id')->index('product_self_consumption_self_consumption_id_foreign');
            $table->integer('quantity')->nullable();
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['self_consumption_id'])->references(['id'])->on('self_consumptions')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_self_consumption', function (Blueprint $table) {
            $table->dropForeign('product_self_consumption_product_id_foreign');
            $table->dropForeign('product_self_consumption_self_consumption_id_foreign');
        });
        Schema::dropIfExists('product_self_consumption');
    }
};
