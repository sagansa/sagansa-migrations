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
        if (!Schema::hasTable('account_cashlesses')) {
            Schema::create('account_cashlesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cashless_provider_id')->nullable()->index('account_cashlesses_cashless_provider_id_foreign');
            $table->unsignedBigInteger('store_id')->index('account_cashlesses_store_id_foreign');
            $table->unsignedBigInteger('store_cashless_id')->nullable()->index('account_cashlesses_store_cashless_id_foreign');
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('no_telp')->nullable();
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign(['cashless_provider_id'], 'account_cashlesses_ibfk_1')->references(['id'])->on('cashless_providers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'], 'account_cashlesses_ibfk_2')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_cashless_id'], 'account_cashlesses_ibfk_3')->references(['id'])->on('store_cashlesses')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_cashlesses', function (Blueprint $table) {
            $table->dropForeign('account_cashlesses_ibfk_1');
            $table->dropForeign('account_cashlesses_ibfk_2');
            $table->dropForeign('account_cashlesses_ibfk_3');
        });
        Schema::dropIfExists('account_cashlesses');
    }
};
