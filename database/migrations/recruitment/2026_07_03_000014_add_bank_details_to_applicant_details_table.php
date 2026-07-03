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
                if (!Schema::hasColumn('applicant_details', 'bank_account_name')) {
                    $table->string('bank_account_name')->nullable();
                }
                if (!Schema::hasColumn('applicant_details', 'bank_account_number')) {
                    $table->string('bank_account_number')->nullable();
                }
                if (!Schema::hasColumn('applicant_details', 'bank_name')) {
                    $table->string('bank_name')->nullable();
                }
                if (!Schema::hasColumn('applicant_details', 'admin_fee')) {
                    $table->decimal('admin_fee', 15, 2)->default(0);
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
                $cols = [];
                if (Schema::hasColumn('applicant_details', 'bank_account_name')) {
                    $cols[] = 'bank_account_name';
                }
                if (Schema::hasColumn('applicant_details', 'bank_account_number')) {
                    $cols[] = 'bank_account_number';
                }
                if (Schema::hasColumn('applicant_details', 'bank_name')) {
                    $cols[] = 'bank_name';
                }
                if (Schema::hasColumn('applicant_details', 'admin_fee')) {
                    $cols[] = 'admin_fee';
                }
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};
