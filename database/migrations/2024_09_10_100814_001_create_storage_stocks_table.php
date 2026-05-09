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
        if (!Schema::hasTable('storage_stocks')) {
            Schema::create('storage_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->unsignedBigInteger('store_id')->index('store_id');
            $table->tinyInteger('status');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('created_by_id');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('approved_by_id');
            $table->timestamps();
            $table->foreign(['store_id'], 'storage_stocks_ibfk_1')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['created_by_id'], 'storage_stocks_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['approved_by_id'], 'storage_stocks_ibfk_3')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_stocks', function (Blueprint $table) {
            $table->dropForeign('storage_stocks_ibfk_1');
            $table->dropForeign('storage_stocks_ibfk_2');
            $table->dropForeign('storage_stocks_ibfk_3');
        });
        Schema::dropIfExists('storage_stocks');
    }
};
