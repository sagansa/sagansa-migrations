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
        if (!Schema::hasTable('hygienes')) {
            Schema::create('hygienes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->index('hygienes_store_id_foreign');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('hygienes_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('hygienes_approved_by_id_foreign');
            $table->timestamps();
            
            
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hygienes', function (Blueprint $table) {
            $table->dropForeign('hygienes_approved_by_id_foreign');
            $table->dropForeign('hygienes_created_by_id_foreign');
            $table->dropForeign('hygienes_store_id_foreign');
        });
        Schema::dropIfExists('hygienes');
    }
};
