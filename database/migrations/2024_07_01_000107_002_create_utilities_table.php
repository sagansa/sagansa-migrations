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
        if (!Schema::hasTable('utilities')) {
            Schema::create('utilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->unique();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('store_id')->nullable()->index('store_id_foreign');
            $table->tinyInteger('category');
            $table->unsignedBigInteger('unit_id')->index('utilities_unit_id_foreign');
            $table->unsignedBigInteger('utility_provider_id')->index('utilities_utility_provider_id_foreign');
            $table->tinyInteger('pre_post');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->foreign(['store_id'], 'store_id_foreign')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['utility_provider_id'])->references(['id'])->on('utility_providers')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilities', function (Blueprint $table) {
            $table->dropForeign('store_id_foreign');
            $table->dropForeign('utilities_unit_id_foreign');
            $table->dropForeign('utilities_utility_provider_id_foreign');
        });
        Schema::dropIfExists('utilities');
    }
};
