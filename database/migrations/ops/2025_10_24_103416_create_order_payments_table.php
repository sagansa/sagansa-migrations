<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->decimal('amount', 10, 2);
            $table->uuid('payment_type_id');
            $table->string('reference')->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->boolean('is_offline')->default(false);
            $table->timestamp('synced_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('payment_type_id')->references('id')->on('payment_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
