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
        if (!Schema::hasTable('sales_order_onlines')) {
            Schema::create('sales_order_onlines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('store_id')->index('sales_order_onlines_store_id_foreign');
            $table->unsignedBigInteger('online_shop_provider_id')->index('sales_order_onlines_online_shop_provider_id_foreign');
            $table->unsignedBigInteger('delivery_service_id')->index('sales_order_onlines_delivery_service_id_foreign');
            $table->date('date');
            $table->unsignedBigInteger('customer_id')->nullable()->index('sales_order_onlines_customer_id_foreign');
            $table->unsignedBigInteger('delivery_address_id')->nullable()->index('sales_order_onlines_delivery_address_id_foreign');
            $table->string('receipt_no')->nullable();
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('sales_order_onlines_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('sales_order_onlines_approved_by_id_foreign');
            $table->string('image_sent')->nullable();
            $table->timestamps();
            
            
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['delivery_address_id'])->references(['id'])->on('delivery_addresses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['delivery_service_id'])->references(['id'])->on('delivery_services')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['online_shop_provider_id'])->references(['id'])->on('online_shop_providers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_onlines', function (Blueprint $table) {
            $table->dropForeign('sales_order_onlines_approved_by_id_foreign');
            $table->dropForeign('sales_order_onlines_created_by_id_foreign');
            $table->dropForeign('sales_order_onlines_customer_id_foreign');
            $table->dropForeign('sales_order_onlines_delivery_address_id_foreign');
            $table->dropForeign('sales_order_onlines_delivery_service_id_foreign');
            $table->dropForeign('sales_order_onlines_online_shop_provider_id_foreign');
            $table->dropForeign('sales_order_onlines_store_id_foreign');
        });
        Schema::dropIfExists('sales_order_onlines');
    }
};
