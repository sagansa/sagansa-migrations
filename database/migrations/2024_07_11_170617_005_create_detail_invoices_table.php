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
        if (!Schema::hasTable('detail_invoices')) {
            Schema::create('detail_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_purchase_id')->index('detail_invoices_invoice_purchase_id_foreign');
            $table->unsignedBigInteger('detail_request_id')->nullable()->index('detail_invoices_detail_request_id_foreign');
            $table->bigInteger('quantity_product');
            $table->decimal('quantity_invoice')->nullable();
            $table->unsignedBigInteger('unit_invoice_id')->nullable()->index('detail_invoices_unit_invoice_id_foreign');
            $table->bigInteger('subtotal_invoice');
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->foreign(['detail_request_id'])->references(['id'])->on('detail_requests')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['invoice_purchase_id'])->references(['id'])->on('invoice_purchases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['unit_invoice_id'])->references(['id'])->on('units')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_invoices', function (Blueprint $table) {
            $table->dropForeign('detail_invoices_detail_request_id_foreign');
            $table->dropForeign('detail_invoices_invoice_purchase_id_foreign');
            $table->dropForeign('detail_invoices_unit_invoice_id_foreign');
        });
        Schema::dropIfExists('detail_invoices');
    }
};
