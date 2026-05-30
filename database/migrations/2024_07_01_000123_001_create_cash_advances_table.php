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
        if (!Schema::hasTable('cash_advances')) {
            Schema::create('cash_advances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->date('date');
            $table->bigInteger('transfer');
            $table->unsignedBigInteger('user_id')->index();
            $table->bigInteger('before');
            $table->bigInteger('purchase');
            $table->bigInteger('remains');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign(['user_id'], 'cash_advances_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_advances', function (Blueprint $table) {
            $table->dropForeign('cash_advances_ibfk_1');
        });
        Schema::dropIfExists('cash_advances');
    }
};
