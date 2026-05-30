<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sanctum tokens live in sagansa_user and must not be duplicated in sagansa_ops.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sanctum tokens live in sagansa_user and must not be dropped from sagansa_ops migrations.
    }
};
