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
        if (!Schema::hasTable('store_consumptions')) {
            Schema::create('store_consumptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->index('store_consumptions_store_id_foreign');
            $table->date('date');
            $table->timestamps();
            $table->foreign(['store_id'], 'store_consumptions_ibfk_1')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_consumptions', function (Blueprint $table) {
            $table->dropForeign('store_consumptions_ibfk_1');
        });
        Schema::dropIfExists('store_consumptions');
    }
};
