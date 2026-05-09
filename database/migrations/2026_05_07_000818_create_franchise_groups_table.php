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
        if (!Schema::hasTable('franchise_groups')) {
            Schema::create('franchise_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->tinyInteger('status');
            $table->unsignedBigInteger('user_id')->nullable()->index('franchise_groups_user_id_foreign');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchise_groups', function (Blueprint $table) {
            
        });
        Schema::dropIfExists('franchise_groups');
    }
};
