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
        if (!Schema::hasTable('delivery_locations')) {
            Schema::create('delivery_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->string('contact_name');
            $table->string('contact_number');
            $table->string('address');
            $table->unsignedBigInteger('province_id')->nullable()->index('delivery_locations_province_id_foreign');
            $table->unsignedBigInteger('regency_id')->nullable()->index('delivery_locations_regency_id_foreign');
            $table->unsignedBigInteger('district_id')->nullable()->index('delivery_locations_district_id_foreign');
            $table->unsignedBigInteger('village_id')->nullable()->index('delivery_locations_village_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('delivery_locations_user_id_foreign');
            $table->timestamps();
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['regency_id'])->references(['id'])->on('cities')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['village_id'])->references(['id'])->on('subdistricts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_locations', function (Blueprint $table) {
            $table->dropForeign('delivery_locations_district_id_foreign');
            $table->dropForeign('delivery_locations_province_id_foreign');
            $table->dropForeign('delivery_locations_regency_id_foreign');
            
            $table->dropForeign('delivery_locations_village_id_foreign');
        });
        Schema::dropIfExists('delivery_locations');
    }
};
