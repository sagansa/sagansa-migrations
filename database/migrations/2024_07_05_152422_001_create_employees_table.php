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
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('identity_no');
            $table->string('fullname');
            $table->string('nickname', 20);
            $table->string('no_telp');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->tinyInteger('gender');
            $table->tinyInteger('religion');
            $table->tinyInteger('marital_status');
            $table->tinyInteger('level_of_education');
            $table->string('major');
            $table->string('fathers_name');
            $table->string('mothers_name');
            $table->text('address');
            $table->unsignedBigInteger('province_id')->nullable()->index('employees_province_id_foreign');
            $table->unsignedBigInteger('regency_id')->nullable()->index('employees_regency_id_foreign');
            $table->unsignedBigInteger('district_id')->nullable()->index('employees_district_id_foreign');
            $table->unsignedBigInteger('village_id')->nullable()->index('employees_village_id_foreign');
            $table->integer('codepos');
            $table->string('gps_location')->nullable();
            $table->string('parents_no_telp');
            $table->string('siblings_name');
            $table->string('siblings_no_telp');
            $table->boolean('bpjs');
            $table->string('driver_license');
            $table->unsignedBigInteger('bank_id')->index('employees_bank_id_foreign');
            $table->string('bank_account_no');
            $table->date('accepted_work_date');
            $table->string('ttd');
            $table->text('notes');
            $table->string('image_identity_id');
            $table->string('image_selfie');
            $table->unsignedBigInteger('user_id')->nullable()->index('employees_user_id_foreign');
            $table->unsignedBigInteger('employee_status_id')->nullable()->index('employees_employee_status_id_foreign');
            $table->timestamps();
            $table->softDeletes();

            $table->primary(['id']);
            $table->foreign(['bank_id'])->references(['id'])->on('banks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['employee_status_id'])->references(['id'])->on('employee_statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['regency_id'])->references(['id'])->on('cities')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['village_id'])->references(['id'])->on('subdistricts')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign('employees_bank_id_foreign');
            $table->dropForeign('employees_district_id_foreign');
            $table->dropForeign('employees_employee_status_id_foreign');
            $table->dropForeign('employees_province_id_foreign');
            $table->dropForeign('employees_regency_id_foreign');
            
            $table->dropForeign('employees_village_id_foreign');
        });
        Schema::dropIfExists('employees');
    }
};
