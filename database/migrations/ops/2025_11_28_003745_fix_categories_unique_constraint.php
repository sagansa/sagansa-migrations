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
        Schema::table('categories', function (Blueprint $table) {
            // Drop the existing unique index on 'name'
            // Laravel usually names indexes as table_column_unique
            $table->dropUnique('categories_name_unique');

            // Add new composite unique index
            $table->unique(['tenant_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'name']);
            $table->unique('name');
        });
    }
};
