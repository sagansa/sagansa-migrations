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
        if (!Schema::hasTable('monthly_salaries')) {
            Schema::create('monthly_salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('presence_id')->index('monthly_salaries_presence_id_foreign');
            $table->bigInteger('amount');
            $table->timestamps();
            $table->foreign(['presence_id'])->references(['id'])->on('presences')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_salaries', function (Blueprint $table) {
            $table->dropForeign('monthly_salaries_presence_id_foreign');
        });
        Schema::dropIfExists('monthly_salaries');
    }
};
