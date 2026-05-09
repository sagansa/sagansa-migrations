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
        if (!Schema::hasTable('transfer_cards')) {
            Schema::create('transfer_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('image')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('from_store_id');
            $table->unsignedBigInteger('to_store_id');
            $table->unsignedBigInteger('sent_by_id');
            $table->unsignedBigInteger('received_by_id');
            $table->enum('for', ['store', 'storage']);
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_cards');
    }
};
