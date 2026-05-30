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
        if (!Schema::hasTable('hygiene_of_rooms')) {
            Schema::create('hygiene_of_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('hygiene_id')->index('hygiene_of_rooms_hygiene_id_foreign');
            $table->unsignedBigInteger('room_id')->index('hygiene_of_rooms_room_id_foreign');
            $table->string('image')->nullable();
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hygiene_of_rooms');
    }
};
