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
        if (!Schema::hasTable('readinesses')) {
            Schema::create('readinesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image_selfie')->nullable();
            $table->string('left_hand')->nullable();
            $table->string('right_hand')->nullable();
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index();
            $table->unsignedBigInteger('approved_by_id')->nullable()->index();
            $table->timestamps();
            $table->foreign(['created_by_id'], 'readinesses_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['approved_by_id'], 'readinesses_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('readinesses', function (Blueprint $table) {
            $table->dropForeign('readinesses_ibfk_1');
            $table->dropForeign('readinesses_ibfk_2');
        });
        Schema::dropIfExists('readinesses');
    }
};
