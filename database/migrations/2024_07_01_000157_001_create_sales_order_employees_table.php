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
        if (!Schema::hasTable('sales_order_employees')) {
            Schema::create('sales_order_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer');
            $table->text('detail_customer');
            $table->unsignedBigInteger('store_id')->index('sales_order_employees_store_id_foreign');
            $table->date('date');
            $table->string('image')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('sales_order_employees_created_by_id_foreign');
            $table->timestamps();
            $table->foreign(['user_id'], 'sales_order_employees_created_by_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_employees', function (Blueprint $table) {
            $table->dropForeign('sales_order_employees_created_by_id_foreign');
            $table->dropForeign('sales_order_employees_store_id_foreign');
        });
        Schema::dropIfExists('sales_order_employees');
    }
};
