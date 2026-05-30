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
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('no_telp')->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('province_id')->nullable()->index('suppliers_province_id_foreign');
            $table->unsignedBigInteger('city_id')->nullable()->index('suppliers_regency_id_foreign');
            $table->unsignedBigInteger('district_id')->nullable()->index('suppliers_district_id_foreign');
            $table->unsignedBigInteger('subdistrict_id')->nullable()->index('suppliers_village_id_foreign');
            $table->unsignedBigInteger('postal_code_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable()->index('suppliers_bank_id_foreign');
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->tinyInteger('status');
            $table->unsignedBigInteger('user_id')->nullable()->index('suppliers_user_id_foreign');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign(['bank_id'])->references(['id'])->on('banks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['city_id'], 'suppliers_regency_id_foreign')->references(['id'])->on('cities')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['subdistrict_id'], 'suppliers_village_id_foreign')->references(['id'])->on('subdistricts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign('suppliers_bank_id_foreign');
            $table->dropForeign('suppliers_district_id_foreign');
            $table->dropForeign('suppliers_province_id_foreign');
            $table->dropForeign('suppliers_regency_id_foreign');
            
            $table->dropForeign('suppliers_village_id_foreign');
        });
        Schema::dropIfExists('suppliers');
    }
};
