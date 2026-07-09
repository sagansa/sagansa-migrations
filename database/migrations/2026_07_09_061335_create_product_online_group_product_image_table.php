<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        if (!Schema::hasTable('product_online_group_product_image')) {
            Schema::create('product_online_group_product_image', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_online_group_id')->index();
                $table->unsignedBigInteger('product_image_id')->index();
                $table->integer('order')->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_online_group_product_image');
    }
};
