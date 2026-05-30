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
        if (!Schema::hasTable('user_cashlesses')) {
            Schema::create('user_cashlesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cashless_provider_id')->nullable()->index('user_cashlesses_cashless_provider_id_foreign');
            $table->unsignedBigInteger('store_id')->index('user_cashlesses_store_id_foreign');
            $table->unsignedBigInteger('store_cashless_id')->nullable()->index('user_cashlesses_store_cashless_id_foreign');
            $table->string('email')->nullable();
            $table->string('username', 50)->nullable();
            $table->string('password')->nullable();
            $table->string('no_telp')->nullable();
            $table->timestamps();
            $table->foreign(['cashless_provider_id'])->references(['id'])->on('cashless_providers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_cashless_id'])->references(['id'])->on('store_cashlesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_cashlesses', function (Blueprint $table) {
            $table->dropForeign('user_cashlesses_cashless_provider_id_foreign');
            $table->dropForeign('user_cashlesses_store_cashless_id_foreign');
            $table->dropForeign('user_cashlesses_store_id_foreign');
        });
        Schema::dropIfExists('user_cashlesses');
    }
};
