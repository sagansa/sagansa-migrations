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
        // This migration isn't needed since the original migration already sets up UUID primary key correctly
        // The personal_access_tokens table already has uuid('id')->primary() from the original migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            //
        });
    }
};
