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
        if (!Schema::hasTable('postal_codes')) {
            Schema::create('postal_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('province_id')->index();
            $table->unsignedBigInteger('city_id')->index();
            $table->unsignedBigInteger('district_id')->index();
            $table->unsignedBigInteger('subdistrict_id')->index();
            $table->integer('postal_code');
            $table->foreign(['province_id'], 'postal_codes_ibfk_1')->references(['id'])->on('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['city_id'], 'postal_codes_ibfk_2')->references(['id'])->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['district_id'], 'postal_codes_ibfk_3')->references(['id'])->on('districts')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['subdistrict_id'], 'postal_codes_ibfk_4')->references(['id'])->on('subdistricts')->onUpdate('no action')->onDelete('no action');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postal_codes', function (Blueprint $table) {
            $table->dropForeign('postal_codes_ibfk_1');
            $table->dropForeign('postal_codes_ibfk_2');
            $table->dropForeign('postal_codes_ibfk_3');
            $table->dropForeign('postal_codes_ibfk_4');
        });
        Schema::dropIfExists('postal_codes');
    }
};
