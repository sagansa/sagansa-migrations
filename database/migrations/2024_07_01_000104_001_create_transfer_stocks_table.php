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
        if (!Schema::hasTable('transfer_stocks')) {
            Schema::create('transfer_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('from_store_id')->index('transfer_stocks_from_store_id_foreign');
            $table->unsignedBigInteger('to_store_id')->index('transfer_stocks_to_store_id_foreign');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('transfer_stocks_approved_by_id_foreign');
            $table->unsignedBigInteger('received_by_id')->index('transfer_stocks_from_employee_id_foreign');
            $table->unsignedBigInteger('sent_by_id')->index('transfer_stocks_to_employee_id_foreign');
            $table->timestamps();
            
            $table->foreign(['from_store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['sent_by_id'], 'transfer_stocks_to_employee_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['to_store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_stocks', function (Blueprint $table) {
            $table->dropForeign('transfer_stocks_approved_by_id_foreign');
            $table->dropForeign('transfer_stocks_from_store_id_foreign');
            $table->dropForeign('transfer_stocks_received_by_id_foreign');
            $table->dropForeign('transfer_stocks_to_employee_id_foreign');
            $table->dropForeign('transfer_stocks_to_store_id_foreign');
        });
        Schema::dropIfExists('transfer_stocks');
    }
};
