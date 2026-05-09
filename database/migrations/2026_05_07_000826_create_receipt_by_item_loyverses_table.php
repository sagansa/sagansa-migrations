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
        if (!Schema::hasTable('receipt_by_item_loyverses')) {
            Schema::create('receipt_by_item_loyverses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('receipt_number');
            $table->string('receipt_type');
            $table->string('category');
            $table->string('sku');
            $table->string('item');
            $table->string('variant');
            $table->string('modifiers_applied')->nullable();
            $table->integer('quantity');
            $table->string('gross_sales');
            $table->string('discounts');
            $table->string('net_sales');
            $table->string('cost_of_goods');
            $table->string('gross_profit');
            $table->string('taxes');
            $table->string('dining_option');
            $table->string('pos');
            $table->string('store');
            $table->string('cashier_name');
            $table->string('customer_name')->nullable();
            $table->string('customer_contacts')->nullable();
            $table->string('comment')->nullable();
            $table->string('status');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_by_item_loyverses');
    }
};
