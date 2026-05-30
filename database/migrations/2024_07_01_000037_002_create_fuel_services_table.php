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
        if (!Schema::hasTable('fuel_services')) {
            Schema::create('fuel_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->date('date');
            $table->unsignedBigInteger('vehicle_id')->index('fuel_services_vehicle_id_foreign');
            $table->unsignedBigInteger('payment_type_id')->index('fuel_services_payment_type_id_foreign');
            $table->tinyInteger('fuel_service');
            $table->decimal('km');
            $table->decimal('liter');
            $table->bigInteger('amount');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('fuel_services_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('fuel_services_approved_by_id_foreign');
            $table->text('notes')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
            $table->unsignedBigInteger('supplier_id')->nullable()->index('fuel_services_supplier_id_foreign');
            $table->foreign(['supplier_id'], 'fuel_services_ibfk_1')->references(['id'])->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_services', function (Blueprint $table) {
            $table->dropForeign('fuel_services_ibfk_1');
        });
        Schema::dropIfExists('fuel_services');
    }
};
