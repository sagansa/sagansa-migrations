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
        if (!Schema::hasTable('sales_order_directs')) {
            Schema::create('sales_order_directs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('delivery_date');
            $table->unsignedBigInteger('delivery_location_id')->nullable()->index('sales_order_directs_delivery_location_id_foreign');
            $table->unsignedBigInteger('delivery_service_id')->index('sales_order_directs_delivery_service_id_foreign');
            $table->unsignedBigInteger('transfer_to_account_id')->index('sales_order_directs_transfer_to_account_id_foreign');
            $table->string('image_transfer')->nullable();
            $table->tinyInteger('payment_status');
            $table->tinyInteger('delivery_status');
            $table->bigInteger('shipping_cost')->nullable();
            $table->unsignedBigInteger('store_id')->nullable()->index('sales_order_directs_store_id_foreign');
            $table->string('image_receipt')->nullable();
            $table->unsignedBigInteger('submitted_by_id')->nullable()->index('sales_order_directs_submitted_by_id_foreign');
            $table->string('received_by')->nullable();
            $table->string('sign')->nullable();
            $table->unsignedBigInteger('order_by_id')->nullable()->index('sales_order_directs_order_by_id_foreign');
            $table->bigInteger('discounts')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign(['delivery_location_id'])->references(['id'])->on('delivery_locations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['delivery_service_id'])->references(['id'])->on('delivery_services')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['transfer_to_account_id'])->references(['id'])->on('transfer_to_accounts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_directs', function (Blueprint $table) {
            $table->dropForeign('sales_order_directs_delivery_location_id_foreign');
            $table->dropForeign('sales_order_directs_delivery_service_id_foreign');
            $table->dropForeign('sales_order_directs_order_by_id_foreign');
            $table->dropForeign('sales_order_directs_store_id_foreign');
            $table->dropForeign('sales_order_directs_submitted_by_id_foreign');
            $table->dropForeign('sales_order_directs_transfer_to_account_id_foreign');
        });
        Schema::dropIfExists('sales_order_directs');
    }
};
