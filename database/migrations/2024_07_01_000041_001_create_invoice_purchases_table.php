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
        if (!Schema::hasTable('invoice_purchases')) {
            Schema::create('invoice_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('payment_type_id')->index('invoice_purchases_payment_type_id_foreign');
            $table->unsignedBigInteger('store_id')->index('invoice_purchases_store_id_foreign');
            $table->unsignedBigInteger('supplier_id')->index('invoice_purchases_supplier_id_foreign');
            $table->date('date');
            $table->bigInteger('taxes');
            $table->bigInteger('discounts');
            $table->bigInteger('total_price')->nullable()->default(0);
            $table->tinyInteger('payment_status');
            $table->tinyInteger('order_status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('invoice_purchases_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('invoice_purchases_approved_id_foreign');
            $table->timestamps();
            $table->foreign(['approved_by_id'], 'invoice_purchases_approved_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            
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
        Schema::table('invoice_purchases', function (Blueprint $table) {
            $table->dropForeign('invoice_purchases_approved_id_foreign');
            $table->dropForeign('invoice_purchases_created_by_id_foreign');
            $table->dropForeign('invoice_purchases_payment_type_id_foreign');
            $table->dropForeign('invoice_purchases_store_id_foreign');
            $table->dropForeign('invoice_purchases_supplier_id_foreign');
        });
        Schema::dropIfExists('invoice_purchases');
    }
};
