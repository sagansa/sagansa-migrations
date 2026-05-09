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
        if (!Schema::hasTable('so_ddetails')) {
            Schema::create('so_ddetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('e_product_id')->index('so_ddetails_e_product_id_foreign');
            $table->bigInteger('quantity');
            $table->decimal('price');
            $table->unsignedBigInteger('sales_order_direct_id')->index('so_ddetails_sales_order_direct_id_foreign');
            $table->timestamps();
            $table->foreign(['e_product_id'])->references(['id'])->on('e_products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['sales_order_direct_id'])->references(['id'])->on('sales_order_directs')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so_ddetails', function (Blueprint $table) {
            $table->dropForeign('so_ddetails_e_product_id_foreign');
            $table->dropForeign('so_ddetails_sales_order_direct_id_foreign');
        });
        Schema::dropIfExists('so_ddetails');
    }
};
