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
        if (!Schema::hasTable('request_stocks')) {
            Schema::create('request_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->index('request_stocks_store_id_foreign');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('request_stocks_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('request_stocks_approved_by_id_foreign');
            $table->timestamps();
            
            
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_stocks', function (Blueprint $table) {
            $table->dropForeign('request_stocks_approved_by_id_foreign');
            $table->dropForeign('request_stocks_created_by_id_foreign');
            $table->dropForeign('request_stocks_store_id_foreign');
        });
        Schema::dropIfExists('request_stocks');
    }
};
