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
        if (!Schema::hasTable('cashless_provider_closing_store')) {
            Schema::create('cashless_provider_closing_store', function (Blueprint $table) {
            $table->unsignedBigInteger('cashless_provider_id')->index('cashless_provider_closing_store_cashless_provider_id_foreign');
            $table->unsignedBigInteger('closing_store_id')->index('cashless_provider_closing_store_closing_store_id_foreign');
            $table->string('image')->nullable();
            $table->bigInteger('bruto_apl_total');
            $table->bigInteger('netto_apl_total')->nullable();
            $table->bigInteger('bruto_real_total')->nullable();
            $table->bigInteger('netto_real_total')->nullable();
            $table->integer('canceled');
            $table->integer('accepted');
            $table->foreign(['cashless_provider_id'])->references(['id'])->on('cashless_providers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['closing_store_id'])->references(['id'])->on('closing_stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashless_provider_closing_store', function (Blueprint $table) {
            $table->dropForeign('cashless_provider_closing_store_cashless_provider_id_foreign');
            $table->dropForeign('cashless_provider_closing_store_closing_store_id_foreign');
        });
        Schema::dropIfExists('cashless_provider_closing_store');
    }
};
