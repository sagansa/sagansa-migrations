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
        if (!Schema::hasTable('detail_advance_purchases')) {
            Schema::create('detail_advance_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index();
            $table->integer('quantity');
            $table->bigInteger('price');
            $table->bigInteger('unit_price');
            $table->unsignedBigInteger('advance_purchase_id')->index();
            $table->timestamps();
            $table->foreign(['product_id'], 'detail_advance_purchases_ibfk_1')->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['advance_purchase_id'], 'detail_advance_purchases_ibfk_2')->references(['id'])->on('advance_purchases')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_advance_purchases', function (Blueprint $table) {
            $table->dropForeign('detail_advance_purchases_ibfk_1');
            $table->dropForeign('detail_advance_purchases_ibfk_2');
        });
        Schema::dropIfExists('detail_advance_purchases');
    }
};
