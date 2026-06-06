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
        if (! Schema::hasTable('applicant_details')) {
            return;
        }

        $columns = [
            'ktp_image' => fn (Blueprint $table) => $table->string('ktp_image')->nullable()->after('gender'),
            'selfie_image' => fn (Blueprint $table) => $table->string('selfie_image')->nullable()->after('ktp_image'),
            'nik' => fn (Blueprint $table) => $table->string('nik')->nullable()->after('user_id'),
            'religion' => fn (Blueprint $table) => $table->string('religion')->nullable()->after('gender'),
            'marital_status' => fn (Blueprint $table) => $table->string('marital_status')->nullable()->after('religion'),
            'children_count' => fn (Blueprint $table) => $table->integer('children_count')->default(0)->after('marital_status'),
            'education_level' => fn (Blueprint $table) => $table->string('education_level')->nullable()->after('children_count'),
            'education_major' => fn (Blueprint $table) => $table->string('education_major')->nullable()->after('education_level'),
            'father_name' => fn (Blueprint $table) => $table->string('father_name')->nullable()->after('education_major'),
            'mother_name' => fn (Blueprint $table) => $table->string('mother_name')->nullable()->after('father_name'),
            'home_location' => fn (Blueprint $table) => $table->text('home_location')->nullable()->after('address'),
            'emergency_phone' => fn (Blueprint $table) => $table->string('emergency_phone')->nullable()->after('phone'),
            'emergency_name' => fn (Blueprint $table) => $table->string('emergency_name')->nullable()->after('emergency_phone'),
            'driver_license' => fn (Blueprint $table) => $table->string('driver_license')->nullable()->after('mother_name'),
        ];

        foreach ($columns as $column => $definition) {
            if (Schema::hasColumn('applicant_details', $column)) {
                continue;
            }

            Schema::table('applicant_details', function (Blueprint $table) use ($definition) {
                $definition($table);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('applicant_details')) {
            return;
        }

        $columns = array_filter([
            'ktp_image',
            'selfie_image',
            'nik',
            'religion',
            'marital_status',
            'children_count',
            'education_level',
            'education_major',
            'father_name',
            'mother_name',
            'home_location',
            'emergency_phone',
            'emergency_name',
            'driver_license',
        ], fn (string $column) => Schema::hasColumn('applicant_details', $column));

        if ($columns === []) {
            return;
        }

        Schema::table('applicant_details', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};
