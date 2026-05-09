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
        if (!Schema::hasTable('request_purchases')) {
            Schema::create('request_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->index('request_purchases_store_id_foreign');
            $table->date('date');
            $table->unsignedBigInteger('user_id')->nullable()->index('request_purchases_user_id_foreign');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_purchases', function (Blueprint $table) {
            $table->dropForeign('request_purchases_store_id_foreign');
            
        });
        Schema::dropIfExists('request_purchases');
    }
};
