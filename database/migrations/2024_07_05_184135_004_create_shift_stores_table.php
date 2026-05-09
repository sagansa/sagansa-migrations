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
        if (!Schema::hasTable('shift_stores')) {
            Schema::create('shift_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->nullable()->unique('shift_stores_store_id_foreign');
            $table->string('name', 50);
            $table->timestamps();
            $table->time('shift_start_time')->nullable();
            $table->time('shift_end_time')->nullable();
            $table->integer('duration')->nullable();
            $table->foreign(['store_id'], 'shift_stores_ibfk_1')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_stores', function (Blueprint $table) {
            $table->dropForeign('shift_stores_ibfk_1');
        });
        Schema::dropIfExists('shift_stores');
    }
};
