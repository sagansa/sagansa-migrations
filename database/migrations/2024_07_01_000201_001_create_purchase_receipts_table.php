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
        if (!Schema::hasTable('purchase_receipts')) {
            Schema::create('purchase_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->bigInteger('nominal_transfer');
            $table->unsignedBigInteger('user_id')->index('purchase_receipts_user_id_foreign');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_receipts', function (Blueprint $table) {
            
        });
        Schema::dropIfExists('purchase_receipts');
    }
};
