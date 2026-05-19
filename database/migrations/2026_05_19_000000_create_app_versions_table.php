<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->id();
            $table->string('app_name'); // e.g., 'presence', 'point_of_sale', 'admin'
            $table->string('version_code'); // e.g., '1.0.0'
            $table->integer('build_number'); // e.g., 1
            $table->string('apk_file')->nullable(); // path to the uploaded APK file
            $table->boolean('is_active')->default(true);
            $table->boolean('is_force_update')->default(false);
            $table->text('release_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_versions');
    }
};
