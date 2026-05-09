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
        if (!Schema::hasTable('monthly_salary_presence')) {
            Schema::create('monthly_salary_presence', function (Blueprint $table) {
            $table->unsignedBigInteger('presence_id')->index('monthly_salary_presence_presence_id_foreign');
            $table->unsignedBigInteger('monthly_salary_id')->index('monthly_salary_presence_monthly_salary_id_foreign');
            $table->foreign(['presence_id'], 'monthly_salary_presence_ibfk_1')->references(['id'])->on('presences')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['monthly_salary_id'], 'monthly_salary_presence_ibfk_2')->references(['id'])->on('monthly_salaries')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_salary_presence', function (Blueprint $table) {
            $table->dropForeign('monthly_salary_presence_ibfk_1');
            $table->dropForeign('monthly_salary_presence_ibfk_2');
        });
        Schema::dropIfExists('monthly_salary_presence');
    }
};
