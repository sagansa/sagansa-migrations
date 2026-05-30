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
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('product_id')->nullable()->index('product_id');
            $table->string('image_url')->nullable();
            $table->integer('order')->nullable();
            $table->foreign(['product_id'], 'product_images_ibfk_1')->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign('product_images_ibfk_1');
        });
        Schema::dropIfExists('product_images');
    }
};
