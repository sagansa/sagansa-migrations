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
        if (!Schema::hasTable('daily_salaries')) {
            Schema::create('daily_salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->nullable()->index('daily_salaries_store_id_foreign');
            $table->unsignedBigInteger('shift_store_id')->nullable()->index('daily_salaries_shift_store_id_foreign');
            $table->date('date')->nullable();
            $table->bigInteger('amount');
            $table->unsignedBigInteger('payment_type_id')->index('daily_salaries_payment_type_id_foreign');
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('presence_id')->nullable()->index('daily_salaries_presence_id_foreign');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('daily_salaries_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('daily_salaries_approved_by_id_foreign');
            $table->timestamps();
            
            
            $table->foreign(['payment_type_id'])->references(['id'])->on('payment_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['presence_id'])->references(['id'])->on('presences')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['shift_store_id'])->references(['id'])->on('shift_stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_salaries', function (Blueprint $table) {
            $table->dropForeign('daily_salaries_approved_by_id_foreign');
            $table->dropForeign('daily_salaries_created_by_id_foreign');
            $table->dropForeign('daily_salaries_payment_type_id_foreign');
            $table->dropForeign('daily_salaries_presence_id_foreign');
            $table->dropForeign('daily_salaries_shift_store_id_foreign');
            $table->dropForeign('daily_salaries_store_id_foreign');
        });
        Schema::dropIfExists('daily_salaries');
    }
};
