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
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->string('ktp_image')->nullable()->after('gender');
            $table->string('selfie_image')->nullable()->after('ktp_image');
            $table->string('nik')->nullable()->after('user_id');
            $table->string('religion')->nullable()->after('gender');
            $table->string('marital_status')->nullable()->after('religion');
            $table->integer('children_count')->default(0)->after('marital_status');
            $table->string('education_level')->nullable()->after('children_count');
            $table->string('education_major')->nullable()->after('education_level');
            $table->string('father_name')->nullable()->after('education_major');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->text('home_location')->nullable()->after('address');
            $table->string('emergency_phone')->nullable()->after('phone');
            $table->string('emergency_name')->nullable()->after('emergency_phone');
            $table->string('driver_license')->nullable()->after('mother_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->dropColumn([
                'ktp_image', 'selfie_image', 'nik', 'religion', 'marital_status', 
                'children_count', 'education_level', 'education_major', 'father_name', 
                'mother_name', 'home_location', 'emergency_phone', 'emergency_name', 'driver_license'
            ]);
        });
    }
};
