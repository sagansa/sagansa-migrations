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
        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('store_id')->constrained()->cascadeOnDelete();
                $table->string('type'); // cash, qris, transfer, debit
                $table->string('name');
                $table->boolean('is_active')->default(true);
                $table->json('details')->nullable(); // for QR image path, bank details, etc.
                $table->boolean('require_proof')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
