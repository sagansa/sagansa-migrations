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
        if (!Schema::hasTable('out_in_products')) {
            Schema::create('out_in_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->tinyInteger('out_in');
            $table->string('to_from', 50);
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('stock_card_id')->index('out_in_products_stock_card_id_foreign');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('out_in_products_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('out_in_products_approved_by_id_foreign');
            $table->timestamps();
            
            
            $table->foreign(['stock_card_id'])->references(['id'])->on('stock_cards')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('out_in_products', function (Blueprint $table) {
            $table->dropForeign('out_in_products_approved_by_id_foreign');
            $table->dropForeign('out_in_products_created_by_id_foreign');
            $table->dropForeign('out_in_products_stock_card_id_foreign');
        });
        Schema::dropIfExists('out_in_products');
    }
};
