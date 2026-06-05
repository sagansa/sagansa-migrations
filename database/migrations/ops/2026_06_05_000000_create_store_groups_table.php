<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        if (! Schema::hasTable('store_groups')) {
            Schema::create('store_groups', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('tenant_id')->index();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();

                $table->unique(['tenant_id', 'name']);
            });
        }

        if (Schema::hasTable('stores') && ! Schema::hasColumn('stores', 'store_group_id')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->uuid('store_group_id')->nullable()->after('tenant_id')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stores') && Schema::hasColumn('stores', 'store_group_id')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('store_group_id');
            });
        }

        Schema::dropIfExists('store_groups');
    }
};
