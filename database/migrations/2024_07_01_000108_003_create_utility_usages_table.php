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
        if (!Schema::hasTable('utility_usages')) {
            Schema::create('utility_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('utility_id')->index('utility_usages_utility_id_foreign');
            $table->decimal('result');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('utility_usages_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('utility_usages_approved_by_id_foreign');
            $table->timestamps();
            
            
            $table->foreign(['utility_id'])->references(['id'])->on('utilities')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utility_usages', function (Blueprint $table) {
            $table->dropForeign('utility_usages_approved_by_id_foreign');
            $table->dropForeign('utility_usages_created_by_id_foreign');
            $table->dropForeign('utility_usages_utility_id_foreign');
        });
        Schema::dropIfExists('utility_usages');
    }
};
