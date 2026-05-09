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
        if (!Schema::hasTable('contract_employees')) {
            Schema::create('contract_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file')->nullable();
            $table->date('from_date');
            $table->date('until_date');
            $table->bigInteger('nominal_guarantee');
            $table->tinyInteger('guarantee');
            $table->unsignedBigInteger('employee_id')->nullable()->index('contract_employees_employee_id_foreign');
            $table->timestamps();
            $table->foreign(['employee_id'])->references(['id'])->on('employees')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_employees', function (Blueprint $table) {
            $table->dropForeign('contract_employees_employee_id_foreign');
        });
        Schema::dropIfExists('contract_employees');
    }
};
