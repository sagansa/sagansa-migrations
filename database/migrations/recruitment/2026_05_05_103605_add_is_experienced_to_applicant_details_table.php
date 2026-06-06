<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_recruitment';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('applicant_details') || Schema::hasColumn('applicant_details', 'is_experienced')) {
            return;
        }

        Schema::table('applicant_details', function (Blueprint $table) {
            $table->boolean('is_experienced')->default(true)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('applicant_details') || ! Schema::hasColumn('applicant_details', 'is_experienced')) {
            return;
        }

        Schema::table('applicant_details', function (Blueprint $table) {
            $table->dropColumn('is_experienced');
        });
    }
};
