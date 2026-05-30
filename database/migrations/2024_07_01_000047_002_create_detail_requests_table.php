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
        if (!Schema::hasTable('detail_requests')) {
            Schema::create('detail_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('detail_requests_product_id_foreign');
            $table->bigInteger('quantity_plan');
            $table->tinyInteger('status')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('request_purchase_id')->index('detail_requests_request_purchase_id_foreign');
            $table->unsignedBigInteger('store_id')->default(150)->index('detail_requests_store_id_foreign');
            $table->unsignedBigInteger('payment_type_id')->index('detail_requests_payment_type_id_foreign');
            $table->timestamps();
            $table->foreign(['payment_type_id'])->references(['id'])->on('payment_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['request_purchase_id'])->references(['id'])->on('request_purchases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_requests', function (Blueprint $table) {
            $table->dropForeign('detail_requests_payment_type_id_foreign');
            $table->dropForeign('detail_requests_product_id_foreign');
            $table->dropForeign('detail_requests_request_purchase_id_foreign');
            $table->dropForeign('detail_requests_store_id_foreign');
        });
        Schema::dropIfExists('detail_requests');
    }
};
