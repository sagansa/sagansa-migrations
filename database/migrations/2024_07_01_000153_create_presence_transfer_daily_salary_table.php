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
        if (!Schema::hasTable('presence_transfer_daily_salary')) {
            Schema::create('presence_transfer_daily_salary', function (Blueprint $table) {
            $table->unsignedBigInteger('presence_id')->index('presence_transfer_daily_salary_presence_id_foreign');
            $table->unsignedBigInteger('transfer_daily_salary_id')->index('presence_transfer_daily_salary_transfer_daily_salary_id_foreign');
            $table->foreign(['presence_id'])->references(['id'])->on('presences')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['transfer_daily_salary_id'])->references(['id'])->on('transfer_daily_salaries')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presence_transfer_daily_salary', function (Blueprint $table) {
            $table->dropForeign('presence_transfer_daily_salary_presence_id_foreign');
            $table->dropForeign('presence_transfer_daily_salary_transfer_daily_salary_id_foreign');
        });
        Schema::dropIfExists('presence_transfer_daily_salary');
    }
};
