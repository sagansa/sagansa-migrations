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
        if (!Schema::hasTable('material_groups')) {
            Schema::create('material_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->tinyInteger('status');
            $table->unsignedBigInteger('user_id')->nullable()->index('material_groups_user_id_foreign');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_groups', function (Blueprint $table) {
            
        });
        Schema::dropIfExists('material_groups');
    }
};
