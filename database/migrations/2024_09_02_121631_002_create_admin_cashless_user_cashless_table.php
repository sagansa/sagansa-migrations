<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('admin_cashless_user_cashless')) {
            Schema::create('admin_cashless_user_cashless', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_cashless_id')->index('admin_cashless_user_cashless_admin_cashless_id_foreign');
            $table->unsignedBigInteger('user_cashless_id')->index('admin_cashless_user_cashless_user_cashless_id_foreign');
            $table->foreign(['admin_cashless_id'])->references(['id'])->on('admin_cashlesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_cashless_id'])->references(['id'])->on('user_cashlesses')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_cashless_user_cashless', function (Blueprint $table) {
            $table->dropForeign('admin_cashless_user_cashless_admin_cashless_id_foreign');
            $table->dropForeign('admin_cashless_user_cashless_user_cashless_id_foreign');
        });
        Schema::dropIfExists('admin_cashless_user_cashless');
    }
};
