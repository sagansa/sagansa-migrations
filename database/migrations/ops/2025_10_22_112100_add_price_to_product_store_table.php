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
        Schema::table('product_store', function (Blueprint $table) {
            if (! Schema::hasColumn('product_store', 'price')) {
                $table->unsignedInteger('price')->nullable()->after('store_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_store', function (Blueprint $table) {
            if (Schema::hasColumn('product_store', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
