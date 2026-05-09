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
        if (!Schema::hasTable('stock_cards')) {
            Schema::create('stock_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('for', ['remaining_store', 'remaining_storage', 'employee_consumption', 'store_consumption']);
            $table->date('date');
            $table->unsignedBigInteger('store_id')->index('stock_cards_store_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('stock_cards_user_id_foreign');
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_cards', function (Blueprint $table) {
            $table->dropForeign('stock_cards_store_id_foreign');
            
        });
        Schema::dropIfExists('stock_cards');
    }
};
