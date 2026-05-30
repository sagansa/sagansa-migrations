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
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('carts_e_product_id_foreign');
            $table->integer('quantity');
            $table->unsignedBigInteger('user_id')->index('carts_user_id_foreign');
            $table->timestamps();
            $table->foreign(['product_id'], 'carts_ibfk_1')->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_ibfk_1');
            
        });
        Schema::dropIfExists('carts');
    }
};
