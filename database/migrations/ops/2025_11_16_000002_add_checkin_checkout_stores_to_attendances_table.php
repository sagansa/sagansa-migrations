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
        Schema::table('attendances', function (Blueprint $table) {
            // Tambahkan field untuk store check-in dan check-out terpisah
            $table->uuid('check_in_store_id')->after('store_id');
            $table->uuid('check_out_store_id')->nullable()->after('check_in_store_id');

            // Foreign key constraints
            $table->foreign('check_in_store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->foreign('check_out_store_id')->references('id')->on('stores')->nullOnDelete();

            // Index untuk performance
            $table->index('check_in_store_id');
            $table->index('check_out_store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['check_in_store_id']);
            $table->dropForeign(['check_out_store_id']);
            $table->dropColumn(['check_in_store_id', 'check_out_store_id']);
        });
    }
};
