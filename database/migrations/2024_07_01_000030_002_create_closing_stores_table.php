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
        if (!Schema::hasTable('closing_stores')) {
            Schema::create('closing_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->index('closing_stores_store_id_foreign');
            $table->unsignedBigInteger('shift_store_id')->index('closing_stores_shift_store_id_foreign');
            $table->date('date');
            $table->bigInteger('cash_from_yesterday');
            $table->bigInteger('cash_for_tomorrow')->nullable();
            $table->unsignedBigInteger('transfer_by_id')->nullable()->index('closing_stores_transfer_by_id_foreign');
            $table->bigInteger('total_cash_transfer')->nullable();
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('closing_stores_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('closing_stores_approved_by_id_foreign');
            $table->timestamps();
            
            
            $table->foreign(['shift_store_id'])->references(['id'])->on('shift_stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('closing_stores', function (Blueprint $table) {
            $table->dropForeign('closing_stores_approved_by_id_foreign');
            $table->dropForeign('closing_stores_created_by_id_foreign');
            $table->dropForeign('closing_stores_shift_store_id_foreign');
            $table->dropForeign('closing_stores_store_id_foreign');
            $table->dropForeign('closing_stores_transfer_by_id_foreign');
        });
        Schema::dropIfExists('closing_stores');
    }
};
