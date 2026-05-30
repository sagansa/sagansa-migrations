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
        if (!Schema::hasTable('admin_cashlesses')) {
            Schema::create('admin_cashlesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cashless_provider_id')->index('admin_cashlesses_cashless_provider_id_foreign');
            $table->string('username', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->foreign(['cashless_provider_id'])->references(['id'])->on('cashless_providers')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_cashlesses', function (Blueprint $table) {
            $table->dropForeign('admin_cashlesses_cashless_provider_id_foreign');
        });
        Schema::dropIfExists('admin_cashlesses');
    }
};
