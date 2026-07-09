<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        if (!Schema::hasTable('product_online_groups')) {
            Schema::create('product_online_groups', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->unsignedDecimal('online_price', 15, 2)->default(0);
                $table->unsignedBigInteger('online_category_id')->index();
                $table->unsignedBigInteger('unit_id')->index();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('user_id')->index();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('online_category_id')->references('id')->on('online_categories')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('unit_id')->references('id')->on('units')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('product_online_group_items')) {
            Schema::create('product_online_group_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('product_online_group_id')->index();
                $table->unsignedBigInteger('product_id')->unique();
                $table->timestamps();

                $table->foreign('product_online_group_id', 'pogi_group_fk')->references('id')->on('product_online_groups')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('product_id', 'pogi_product_fk')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('product_online_group_price_tiers')) {
            Schema::create('product_online_group_price_tiers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('product_online_group_id')->index();
                $table->integer('min_quantity');
                $table->integer('max_quantity')->nullable();
                $table->unsignedDecimal('price', 15, 2);
                $table->string('label')->nullable();
                $table->timestamps();

                $table->foreign('product_online_group_id', 'pogpt_group_fk')->references('id')->on('product_online_groups')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('carts') && !Schema::hasColumn('carts', 'product_online_group_id')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->unsignedBigInteger('product_online_group_id')->nullable()->after('product_id');
                $table->foreign('product_online_group_id', 'carts_group_fk')->references('id')->on('product_online_groups')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('detail_sales_orders') && !Schema::hasColumn('detail_sales_orders', 'product_online_group_id')) {
            Schema::table('detail_sales_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('product_online_group_id')->nullable()->after('product_id');
                $table->foreign('product_online_group_id', 'dso_group_fk')->references('id')->on('product_online_groups')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detail_sales_orders') && Schema::hasColumn('detail_sales_orders', 'product_online_group_id')) {
            Schema::table('detail_sales_orders', function (Blueprint $table) {
                $table->dropForeign('dso_group_fk');
                $table->dropColumn('product_online_group_id');
            });
        }

        if (Schema::hasTable('carts') && Schema::hasColumn('carts', 'product_online_group_id')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropForeign('carts_group_fk');
                $table->dropColumn('product_online_group_id');
            });
        }

        Schema::dropIfExists('product_online_group_price_tiers');
        Schema::dropIfExists('product_online_group_items');
        Schema::dropIfExists('product_online_groups');
    }
};
