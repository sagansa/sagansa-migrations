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
        if (Schema::hasTable('applicant_details')) {
            Schema::table('applicant_details', function (Blueprint $table) {
                if (!Schema::hasColumn('applicant_details', 'join_date')) {
                    $table->date('join_date')->nullable()->after('user_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('applicant_details')) {
            Schema::table('applicant_details', function (Blueprint $table) {
                if (Schema::hasColumn('applicant_details', 'join_date')) {
                    $table->dropColumn('join_date');
                }
            });
        }
    }
};
