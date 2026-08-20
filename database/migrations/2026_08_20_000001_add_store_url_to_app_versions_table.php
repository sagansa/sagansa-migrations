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
        if (Schema::hasTable('app_versions') && !Schema::hasColumn('app_versions', 'store_url')) {
            Schema::table('app_versions', function (Blueprint $table) {
                // App Store / Play Store listing URL, used for iOS clients which cannot install an APK
                $table->string('store_url')->nullable()->after('apk_file');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('app_versions') && Schema::hasColumn('app_versions', 'store_url')) {
            Schema::table('app_versions', function (Blueprint $table) {
                $table->dropColumn('store_url');
            });
        }
    }
};
