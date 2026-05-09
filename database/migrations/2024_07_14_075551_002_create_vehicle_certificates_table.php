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
        if (!Schema::hasTable('vehicle_certificates')) {
            Schema::create('vehicle_certificates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_id')->index('vehicle_certificates_vehicle_id_foreign');
            $table->tinyInteger('BPKB');
            $table->tinyInteger('STNK');
            $table->string('name');
            $table->string('brand');
            $table->string('type');
            $table->string('category');
            $table->string('model');
            $table->year('manufacture_year');
            $table->string('cylinder_capacity');
            $table->string('vehilce_identity_no');
            $table->string('engine_no');
            $table->string('color');
            $table->string('type_fuel');
            $table->string('lisence_plate_color');
            $table->string('registration_year');
            $table->string('bpkb_no');
            $table->string('location_code');
            $table->string('registration_queue_no');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign(['vehicle_id'])->references(['id'])->on('vehicles')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_certificates', function (Blueprint $table) {
            $table->dropForeign('vehicle_certificates_vehicle_id_foreign');
        });
        Schema::dropIfExists('vehicle_certificates');
    }
};
