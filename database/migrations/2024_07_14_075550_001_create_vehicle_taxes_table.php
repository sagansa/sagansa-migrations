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
        if (!Schema::hasTable('vehicle_taxes')) {
            Schema::create('vehicle_taxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('vehicle_id')->index('vehicle_taxes_vehicle_id_foreign');
            $table->bigInteger('amount_tax');
            $table->date('expired_date');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id_foreign');
            $table->timestamps();
            $table->foreign(['user_id'], 'user_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['vehicle_id'])->references(['id'])->on('vehicles')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_taxes', function (Blueprint $table) {
            $table->dropForeign('user_id_foreign');
            $table->dropForeign('vehicle_taxes_vehicle_id_foreign');
        });
        Schema::dropIfExists('vehicle_taxes');
    }
};
