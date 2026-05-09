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
        if (!Schema::hasTable('delivery_addresses')) {
            Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('for')->nullable();
            $table->string('name')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_telp_no')->nullable();
            $table->string('address');
            $table->unsignedBigInteger('province_id')->nullable()->index('delivery_addresses_province_id_foreign');
            $table->unsignedBigInteger('city_id')->nullable()->index('delivery_addresses_regency_id_foreign');
            $table->unsignedBigInteger('district_id')->nullable()->index('delivery_addresses_district_id_foreign');
            $table->unsignedBigInteger('subdistrict_id')->nullable()->index('delivery_addresses_village_id_foreign');
            $table->unsignedBigInteger('postal_code_id')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable()->index('delivery_addresses_customer_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('delivery_addresses_user_id_foreign');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'delivery_addresses_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['city_id'], 'delivery_addresses_regency_id_foreign')->references(['id'])->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['subdistrict_id'], 'delivery_addresses_village_id_foreign')->references(['id'])->on('subdistricts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_addresses', function (Blueprint $table) {
            $table->dropForeign('delivery_addresses_customer_id_foreign');
            $table->dropForeign('delivery_addresses_district_id_foreign');
            $table->dropForeign('delivery_addresses_ibfk_1');
            $table->dropForeign('delivery_addresses_province_id_foreign');
            $table->dropForeign('delivery_addresses_regency_id_foreign');
            $table->dropForeign('delivery_addresses_village_id_foreign');
        });
        Schema::dropIfExists('delivery_addresses');
    }
};
