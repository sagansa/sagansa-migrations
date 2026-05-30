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
        if (!Schema::hasTable('movement_assets')) {
            Schema::create('movement_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->string('qr_code')->nullable();
            $table->unsignedBigInteger('product_id')->index('movement_assets_product_id_foreign');
            $table->integer('good_cond_qty');
            $table->integer('bad_cond_qty');
            $table->unsignedBigInteger('user_id')->nullable()->index('movement_assets_user_id_foreign');
            $table->unsignedBigInteger('store_asset_id')->index('movement_assets_store_id_foreign');
            $table->timestamps();
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_asset_id'], 'movement_assets_store_id_foreign')->references(['id'])->on('store_assets')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movement_assets', function (Blueprint $table) {
            $table->dropForeign('movement_assets_product_id_foreign');
            $table->dropForeign('movement_assets_store_id_foreign');
            
        });
        Schema::dropIfExists('movement_assets');
    }
};
