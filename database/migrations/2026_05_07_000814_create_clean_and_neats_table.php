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
        if (!Schema::hasTable('clean_and_neats')) {
            Schema::create('clean_and_neats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('left_hand')->nullable();
            $table->string('right_hand')->nullable();
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('clean_and_neats_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('clean_and_neats_approved_by_id_foreign');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clean_and_neats', function (Blueprint $table) {
            $table->dropForeign('clean_and_neats_approved_by_id_foreign');
            $table->dropForeign('clean_and_neats_created_by_id_foreign');
        });
        Schema::dropIfExists('clean_and_neats');
    }
};
