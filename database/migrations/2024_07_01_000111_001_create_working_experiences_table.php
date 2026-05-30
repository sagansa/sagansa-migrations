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
        if (!Schema::hasTable('working_experiences')) {
            Schema::create('working_experiences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id')->nullable()->index('working_experiences_employee_id_foreign');
            $table->string('place');
            $table->string('position');
            $table->string('salary_per_month');
            $table->string('previous_boss_name')->nullable();
            $table->string('previous_boss_no')->nullable();
            $table->date('from_date');
            $table->date('until_date');
            $table->text('reason');
            $table->timestamps();
            $table->foreign(['employee_id'])->references(['id'])->on('employees')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('working_experiences', function (Blueprint $table) {
            $table->dropForeign('working_experiences_employee_id_foreign');
        });
        Schema::dropIfExists('working_experiences');
    }
};
