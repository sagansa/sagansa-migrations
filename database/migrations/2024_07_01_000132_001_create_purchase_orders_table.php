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
        if (!Schema::hasTable('purchase_orders')) {
            Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_type_id')->index('purchase_orders_payment_type_id_foreign');
            $table->unsignedBigInteger('store_id')->index('purchase_orders_store_id_foreign');
            $table->unsignedBigInteger('supplier_id')->index('purchase_orders_supplier_id_foreign');
            $table->date('date');
            $table->bigInteger('taxes');
            $table->bigInteger('discounts');
            $table->text('notes')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('payment_status');
            $table->tinyInteger('order_status');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('purchase_orders_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('purchase_orders_approved_by_id_foreign');
            $table->timestamps();
            
            
            $table->foreign(['payment_type_id'])->references(['id'])->on('payment_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign('purchase_orders_approved_by_id_foreign');
            $table->dropForeign('purchase_orders_created_by_id_foreign');
            $table->dropForeign('purchase_orders_payment_type_id_foreign');
            $table->dropForeign('purchase_orders_store_id_foreign');
            $table->dropForeign('purchase_orders_supplier_id_foreign');
        });
        Schema::dropIfExists('purchase_orders');
    }
};
