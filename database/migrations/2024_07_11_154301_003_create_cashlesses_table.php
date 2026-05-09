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
        if (!Schema::hasTable('cashlesses')) {
            Schema::create('cashlesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_cashless_id')->index('cashlesses_account_cashless_id_foreign');
            $table->string('image')->nullable();
            $table->bigInteger('bruto_apl');
            $table->bigInteger('netto_apl')->nullable();
            $table->bigInteger('bruto_real')->nullable();
            $table->bigInteger('netto_real')->nullable();
            $table->string('image_canceled')->nullable();
            $table->integer('canceled')->nullable();
            $table->unsignedBigInteger('closing_store_id')->index('cashlesses_closing_store_id_foreign');
            $table->timestamps();
            $table->foreign(['account_cashless_id'])->references(['id'])->on('account_cashlesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['closing_store_id'])->references(['id'])->on('closing_stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashlesses', function (Blueprint $table) {
            $table->dropForeign('cashlesses_account_cashless_id_foreign');
            $table->dropForeign('cashlesses_closing_store_id_foreign');
        });
        Schema::dropIfExists('cashlesses');
    }
};
