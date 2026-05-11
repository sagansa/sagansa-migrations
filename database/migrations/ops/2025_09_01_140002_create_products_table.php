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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->nullable();
            $table->uuid('unit_id')->nullable();
            $table->uuid('category_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');
            $table->string('image')->nullable();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('request')->default(true);
            $table->boolean('remaining')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['category_id']);
            $table->index(['tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
