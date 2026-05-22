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
        Schema::table('order_item_modifications', function (Blueprint $table) {
            if (!Schema::hasColumn('order_item_modifications', 'tenant_id')) {
                            $table->uuid('tenant_id')->after('id')->nullable()->index();            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item_modifications', function (Blueprint $table) {
            if (Schema::hasColumn('order_item_modifications', 'tenant_id')) {
                            $table->dropColumn('tenant_id');            }
        });
    }
};
