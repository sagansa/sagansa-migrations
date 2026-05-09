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
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->string('no_register', 15);
            $table->tinyInteger('type');
            $table->unsignedBigInteger('store_id')->index('vehicles_store_id_foreign');
            $table->tinyInteger('status');
            $table->unsignedBigInteger('user_id')->nullable()->index('vehicles_user_id_foreign');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign('vehicles_store_id_foreign');
            
        });
        Schema::dropIfExists('vehicles');
    }
};
