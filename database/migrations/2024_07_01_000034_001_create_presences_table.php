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
        if (!Schema::hasTable('presences')) {
            Schema::create('presences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->nullable()->index('presences_store_id_foreign');
            $table->unsignedBigInteger('shift_store_id')->nullable()->index('presences_shift_store_id_foreign');
            $table->integer('status')->nullable();
            $table->string('image_in')->nullable();
            $table->dateTime('check_in');
            $table->text('latitude_in')->nullable();
            $table->text('longitude_in')->nullable();
            $table->string('image_out')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->text('latitude_out')->nullable();
            $table->text('longitude_out')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('presences_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('presences_approved_by_id_foreign');
            $table->timestamps();
            $table->softDeletes();
            
            
            $table->foreign(['store_id'], 'presences_ibfk_1')->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['shift_store_id'], 'presences_ibfk_2')->references(['id'])->on('shift_stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropForeign('presences_approved_by_id_foreign');
            $table->dropForeign('presences_created_by_id_foreign');
            $table->dropForeign('presences_ibfk_1');
            $table->dropForeign('presences_ibfk_2');
        });
        Schema::dropIfExists('presences');
    }
};
