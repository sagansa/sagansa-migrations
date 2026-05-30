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
        if (!Schema::hasTable('sops')) {
            Schema::create('sops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->integer('revision');
            $table->string('file')->nullable();
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sops');
    }
};
