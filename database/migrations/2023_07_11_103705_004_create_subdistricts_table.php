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
        if (!Schema::hasTable('subdistricts')) {
            Schema::create('subdistricts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('district_id')->index('villages_district_id_foreign');
            $table->foreign(['district_id'], 'villages_district_id_foreign')->references(['id'])->on('districts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subdistricts', function (Blueprint $table) {
            $table->dropForeign('villages_district_id_foreign');
        });
        Schema::dropIfExists('subdistricts');
    }
};
