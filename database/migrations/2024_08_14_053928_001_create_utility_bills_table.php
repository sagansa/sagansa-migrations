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
        if (!Schema::hasTable('utility_bills')) {
            Schema::create('utility_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('utility_id')->index('utility_bills_utility_id_foreign');
            $table->string('image')->nullable();
            $table->date('date');
            $table->string('amount');
            $table->decimal('initial_indicator');
            $table->decimal('last_indicator');
            $table->timestamps();
            $table->foreign(['utility_id'])->references(['id'])->on('utilities')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utility_bills', function (Blueprint $table) {
            $table->dropForeign('utility_bills_utility_id_foreign');
        });
        Schema::dropIfExists('utility_bills');
    }
};
