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
        // Spatie permission tables live in sagansa_user.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Spatie permission tables live in sagansa_user.
    }
};
