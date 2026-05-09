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

        if (!Schema::hasTable('cache_looks')) {
            if (!Schema::hasTable('cache_looks')) {
        Schema::create('cache_looks', function (Blueprint $table) {
                $table->string('key');
                $table->string('owner');
                $table->integer('expiration');
    });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_looks');
    }
};
