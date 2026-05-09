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
        if (!Schema::hasTable('stores')) {
            Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('nickname', 50);
            $table->string('no_telp')->nullable();
            $table->string('email')->unique();
            $table->unsignedBigInteger('user_id')->nullable()->index('stores_user_id_foreign');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->decimal('latitude', 16, 12)->nullable();
            $table->decimal('longitude', 16, 12)->nullable();
            $table->double('radius')->nullable();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            
        });
        Schema::dropIfExists('stores');
    }
};
