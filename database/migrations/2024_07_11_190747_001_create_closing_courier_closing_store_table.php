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
        if (!Schema::hasTable('closing_courier_closing_store')) {
            Schema::create('closing_courier_closing_store', function (Blueprint $table) {
            $table->unsignedBigInteger('closing_store_id')->index('closing_courier_closing_store_closing_store_id_foreign');
            $table->unsignedBigInteger('closing_courier_id')->index('closing_courier_closing_store_closing_courier_id_foreign');
            $table->foreign(['closing_courier_id'])->references(['id'])->on('closing_couriers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['closing_store_id'])->references(['id'])->on('closing_stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('closing_courier_closing_store', function (Blueprint $table) {
            $table->dropForeign('closing_courier_closing_store_closing_courier_id_foreign');
            $table->dropForeign('closing_courier_closing_store_closing_store_id_foreign');
        });
        Schema::dropIfExists('closing_courier_closing_store');
    }
};
