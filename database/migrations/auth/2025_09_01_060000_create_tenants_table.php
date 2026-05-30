<?php

use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    protected $connection = 'mysql_auth';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tenants are owned by apps/ops and live in sagansa_ops.
        // Auth keeps only user/auth tables in sagansa_user.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tenants are not created by auth migrations.
    }
};
