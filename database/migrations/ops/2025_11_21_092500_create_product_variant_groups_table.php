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
        Schema::create('product_variant_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->string('name');
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->uuid('product_variant_group_id')->nullable()->after('product_id');
            $table->foreign('product_variant_group_id')->references('id')->on('product_variant_groups')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['product_variant_group_id']);
            $table->dropColumn('product_variant_group_id');
        });

        Schema::dropIfExists('product_variant_groups');
    }
};
