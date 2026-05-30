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
        if (!Schema::hasTable('contract_locations')) {
            Schema::create('contract_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->index('contract_locations_store_id_foreign');
            $table->string('file')->nullable();
            $table->string('address');
            $table->unsignedBigInteger('province_id')->nullable()->index('contract_locations_province_id_foreign');
            $table->unsignedBigInteger('regency_id')->nullable()->index('contract_locations_regency_id_foreign');
            $table->unsignedBigInteger('district_id')->nullable()->index('contract_locations_district_id_foreign');
            $table->unsignedBigInteger('village_id')->nullable()->index('contract_locations_village_id_foreign');
            $table->integer('codepos')->nullable();
            $table->string('gps_location')->nullable();
            $table->date('from_date');
            $table->date('until_date');
            $table->string('contact_person');
            $table->string('no_contact_person');
            $table->bigInteger('nominal_contract_per_year');
            $table->timestamps();
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['regency_id'])->references(['id'])->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['village_id'])->references(['id'])->on('subdistricts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_locations', function (Blueprint $table) {
            $table->dropForeign('contract_locations_district_id_foreign');
            $table->dropForeign('contract_locations_province_id_foreign');
            $table->dropForeign('contract_locations_regency_id_foreign');
            $table->dropForeign('contract_locations_store_id_foreign');
            $table->dropForeign('contract_locations_village_id_foreign');
        });
        Schema::dropIfExists('contract_locations');
    }
};
