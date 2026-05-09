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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug', 50);
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('request');
            $table->tinyInteger('remaining');
            $table->unsignedBigInteger('unit_id')->index('products_unit_id_foreign');
            $table->unsignedBigInteger('material_group_id')->index('products_material_group_id_foreign');
            $table->unsignedBigInteger('franchise_group_id')->nullable()->index('products_franchise_group_id_foreign');
            $table->unsignedBigInteger('payment_type_id')->index('products_payment_type_id_foreign');
            $table->unsignedBigInteger('online_category_id')->index('products_online_category_id_foreign');
            $table->unsignedBigInteger('product_group_id')->nullable()->index('products_product_group_id_foreign');
            $table->bigInteger('restaurant_category_id')->nullable();
            $table->unsignedBigInteger('user_id')->index('products_user_id_foreign');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign(['franchise_group_id'])->references(['id'])->on('franchise_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['material_group_id'])->references(['id'])->on('material_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['online_category_id'])->references(['id'])->on('online_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['payment_type_id'])->references(['id'])->on('payment_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['product_group_id'])->references(['id'])->on('product_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_franchise_group_id_foreign');
            $table->dropForeign('products_material_group_id_foreign');
            $table->dropForeign('products_online_category_id_foreign');
            $table->dropForeign('products_payment_type_id_foreign');
            $table->dropForeign('products_product_group_id_foreign');
            $table->dropForeign('products_unit_id_foreign');
            
        });
        Schema::dropIfExists('products');
    }
};
