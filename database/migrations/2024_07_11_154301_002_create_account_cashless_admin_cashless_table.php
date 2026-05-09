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
        if (!Schema::hasTable('account_cashless_admin_cashless')) {
            Schema::create('account_cashless_admin_cashless', function (Blueprint $table) {
            $table->unsignedBigInteger('account_cashless_id')->index('account_cashless_admin_cashless_account_cashless_id_foreign');
            $table->unsignedBigInteger('admin_cashless_id')->index('account_cashless_admin_cashless_admin_cashless_id_foreign');
            $table->foreign(['account_cashless_id'])->references(['id'])->on('account_cashlesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['admin_cashless_id'])->references(['id'])->on('admin_cashlesses')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_cashless_admin_cashless', function (Blueprint $table) {
            $table->dropForeign('account_cashless_admin_cashless_account_cashless_id_foreign');
            $table->dropForeign('account_cashless_admin_cashless_admin_cashless_id_foreign');
        });
        Schema::dropIfExists('account_cashless_admin_cashless');
    }
};
