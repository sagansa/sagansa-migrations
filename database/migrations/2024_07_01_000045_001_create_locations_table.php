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
        if (!Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('store_id')->index();
            $table->string('contact_person_name');
            $table->string('contact_person_number');
            $table->string('address');
            $table->unsignedBigInteger('province_id')->index();
            $table->unsignedBigInteger('city_id')->index();
            $table->unsignedBigInteger('district_id')->index();
            $table->unsignedBigInteger('subdistrict_id')->index();
            $table->unsignedBigInteger('postal_code_id')->index('postal_code_id');
            $table->timestamps();
            $table->foreign(['store_id'], 'locations_ibfk_1')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['province_id'], 'locations_ibfk_2')->references(['id'])->on('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['city_id'], 'locations_ibfk_3')->references(['id'])->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['district_id'], 'locations_ibfk_4')->references(['id'])->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['subdistrict_id'], 'locations_ibfk_5')->references(['id'])->on('subdistricts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['postal_code_id'], 'locations_ibfk_6')->references(['id'])->on('postal_codes')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign('locations_ibfk_1');
            $table->dropForeign('locations_ibfk_2');
            $table->dropForeign('locations_ibfk_3');
            $table->dropForeign('locations_ibfk_4');
            $table->dropForeign('locations_ibfk_5');
            $table->dropForeign('locations_ibfk_6');
        });
        Schema::dropIfExists('locations');
    }
};
