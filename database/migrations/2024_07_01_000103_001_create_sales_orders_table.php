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
        if (!Schema::hasTable('sales_orders')) {
            Schema::create('sales_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('for', ['1', '2', '3']);
            $table->date('delivery_date');
            $table->unsignedBigInteger('online_shop_provider_id')->nullable()->index();
            $table->unsignedBigInteger('delivery_service_id')->nullable()->index();
            $table->unsignedBigInteger('delivery_address_id')->nullable()->index();
            $table->unsignedBigInteger('transfer_to_account_id')->nullable()->index();
            $table->string('image_payment')->nullable();
            $table->tinyInteger('payment_status');
            $table->tinyInteger('delivery_status');
            $table->bigInteger('shipping_cost')->nullable();
            $table->unsignedBigInteger('store_id')->nullable()->index();
            $table->string('receipt_no')->nullable();
            $table->string('image_delivery')->nullable();
            $table->unsignedBigInteger('ordered_by_id')->nullable()->index();
            $table->unsignedBigInteger('assigned_by_id')->nullable()->index();
            $table->text('notes')->nullable();
            $table->bigInteger('total_price');
            $table->timestamps();
            $table->string('received_by')->nullable();
            $table->foreign(['online_shop_provider_id'], 'sales_orders_ibfk_1')->references(['id'])->on('online_shop_providers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['delivery_service_id'], 'sales_orders_ibfk_2')->references(['id'])->on('delivery_services')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['delivery_address_id'], 'sales_orders_ibfk_3')->references(['id'])->on('delivery_addresses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['transfer_to_account_id'], 'sales_orders_ibfk_4')->references(['id'])->on('transfer_to_accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'], 'sales_orders_ibfk_5')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ordered_by_id'], 'sales_orders_ibfk_6')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['assigned_by_id'], 'sales_orders_ibfk_7')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropForeign('sales_orders_ibfk_1');
            $table->dropForeign('sales_orders_ibfk_2');
            $table->dropForeign('sales_orders_ibfk_3');
            $table->dropForeign('sales_orders_ibfk_4');
            $table->dropForeign('sales_orders_ibfk_5');
            $table->dropForeign('sales_orders_ibfk_6');
            $table->dropForeign('sales_orders_ibfk_7');
        });
        Schema::dropIfExists('sales_orders');
    }
};
