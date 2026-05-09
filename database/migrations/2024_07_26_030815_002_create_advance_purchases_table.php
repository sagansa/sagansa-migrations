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
        if (!Schema::hasTable('advance_purchases')) {
            Schema::create('advance_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('cash_advance_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->unsignedBigInteger('store_id')->index();
            $table->date('date');
            $table->bigInteger('subtotal_price');
            $table->bigInteger('discount_price');
            $table->bigInteger('total_price');
            $table->unsignedBigInteger('user_id')->index();
            $table->tinyInteger('status')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign(['supplier_id'], 'advance_purchases_ibfk_1')->references(['id'])->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'], 'advance_purchases_ibfk_2')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'advance_purchases_ibfk_3')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['cash_advance_id'], 'advance_purchases_ibfk_4')->references(['id'])->on('cash_advances')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_purchases', function (Blueprint $table) {
            $table->dropForeign('advance_purchases_ibfk_1');
            $table->dropForeign('advance_purchases_ibfk_2');
            $table->dropForeign('advance_purchases_ibfk_3');
            $table->dropForeign('advance_purchases_ibfk_4');
        });
        Schema::dropIfExists('advance_purchases');
    }
};
