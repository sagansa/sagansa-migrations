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
        if (!Schema::hasTable('closing_couriers')) {
            Schema::create('closing_couriers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_id')->index('closing_couriers_bank_id_foreign');
            $table->bigInteger('total_cash_to_transfer');
            $table->string('image')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('status');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('closing_couriers_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('closing_couriers_approved_by_id_foreign');
            $table->timestamps();
            
            $table->foreign(['bank_id'])->references(['id'])->on('banks')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('closing_couriers', function (Blueprint $table) {
            $table->dropForeign('closing_couriers_approved_by_id_foreign');
            $table->dropForeign('closing_couriers_bank_id_foreign');
            $table->dropForeign('closing_couriers_created_by_id_foreign');
        });
        Schema::dropIfExists('closing_couriers');
    }
};
